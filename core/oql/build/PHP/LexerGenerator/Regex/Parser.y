%name PHP_LexerGenerator_Regex_
%include {
require_once 'PHP/LexerGenerator/Exception.php';
}
%declare_class {class PHP_LexerGenerator_Regex_Parser}
%syntax_error {
/* ?><?php */
    // we need to add auto-escaping of all stuff that needs it for result.
    // and then validate the original regex only
    echo "Syntax Error on line " . $this->_lex->line . ": token '" . 
        $this->_lex->value . "' while parsing rule:";
    foreach ($this->yystack as $entry) {
        echo $this->tokenName($entry->major) . ' ';
    }
    foreach ($this->yy_get_expected_tokens($yymajor) as $token) {
        $expect[] = self::$yyTokenName[$token];
    }
    throw new Exception('Unexpected ' . $this->tokenName($yymajor) . '(' . $TOKEN
        . '), expected one of: ' . implode(',', $expect));
}
%include_class {
    private $_lex;
    private $_subpatterns;
    private $_updatePattern;
    private $_patternIndex;
    public $result;
    function __construct($lex)
    {
        $this->result = new PHP_LexerGenerator_ParseryyToken('');
        $this->_lex = $lex;
        $this->_subpatterns = 0;
        $this->_patternIndex = 1;
    }

    function reset($patternIndex, $updatePattern = false)
    {
        $this->_updatePattern = $updatePattern;
        $this->_patternIndex = $patternIndex;
        $this->_subpatterns = 0;
        $this->result = new PHP_LexerGenerator_ParseryyToken('');
    }
}

%left OPENPAREN OPENASSERTION BAR.
%right MULTIPLIER.

start ::= pattern(B). {
    B->string = str_replace('"', '\\"', B->string);
    $x = B->metadata;
    $x['subpatterns'] = $this->_subpatterns;
    B->metadata = $x;
    $this->_subpatterns = 0;
    $this->result = B;
}

pattern ::= MATCHSTART(B) basic_pattern MATCHEND(C). {
    throw new PHP_LexerGenerator_Exception('Cannot include start match "' .
        B . '" or end match "' . C . '"');
}
pattern ::= MATCHSTART basic_pattern. {
    throw new PHP_LexerGenerator_Exception('Cannot include start match "' .
        B . '"');
}
pattern ::= basic_pattern MATCHEND(C). {
    throw new PHP_LexerGenerator_Exception('Cannot include end match "' . C . '"');
}
pattern(A) ::= basic_pattern(B). {A = B;}
pattern(A) ::= pattern(B) BAR pattern(C). {
    A = new PHP_LexerGenerator_ParseryyToken(B->string . '|' . C->string, array(
        'pattern' => B['pattern'] . '|' . C['pattern']));
}

basic_pattern(A) ::= basic_text(B). {A = B;}
basic_pattern(A) ::= character_class(B). {A = B;}
basic_pattern ::= assertion.
basic_pattern(A) ::= grouping(B). {A = B;}
basic_pattern(A) ::= lookahead(B). {A = B;}
basic_pattern ::= lookbehind.
basic_pattern(A) ::= subpattern(B). {A = B;}
basic_pattern(A) ::= onceonly(B). {A = B;}
basic_pattern(A) ::= comment(B). {A = B;}
basic_pattern(A) ::= recur(B). {A = B;}
basic_pattern(A) ::= conditional(B). {A = B;}
basic_pattern(A) ::= basic_pattern(P) basic_text(B). {
    A = new PHP_LexerGenerator_ParseryyToken(P->string . B->string, array(
        'pattern' => P['pattern'] . B['pattern']));
}
basic_pattern(A) ::= basic_pattern(P) character_class(B). {
    A = new PHP_LexerGenerator_ParseryyToken(P->string . B->string, array(
        'pattern' => P['pattern'] . B['pattern']));
}
basic_pattern ::= basic_pattern assertion.
basic_pattern(A) ::= basic_pattern(P) grouping(B). {
    A = new PHP_LexerGenerator_ParseryyToken(P->string . B->string, array(
        'pattern' => P['pattern'] . B['pattern']));
}
basic_pattern(A) ::= basic_pattern(P) lookahead(B). {
    A = new PHP_LexerGenerator_ParseryyToken(P->string . B->string, array(
        'pattern' => P['pattern'] . B['pattern']));
}
basic_pattern ::= basic_pattern lookbehind.
basic_pattern(A) ::= basic_pattern(P) subpattern(B). {
    A = new PHP_LexerGenerator_ParseryyToken(P->string . B->string, array(
        'pattern' => P['pattern'] . B['pattern']));
}
basic_pattern(A) ::= basic_pattern(P) onceonly(B). {
    A = new PHP_LexerGenerator_ParseryyToken(P->string . B->string, array(
        'pattern' => P['pattern'] . B['pattern']));
}
basic_pattern(A) ::= basic_pattern(P) comment(B). {
    A = new PHP_LexerGenerator_ParseryyToken(P->string . B->string, array(
        'pattern' => P['pattern'] . B['pattern']));
}
basic_pattern(A) ::= basic_pattern(P) recur(B). {
    A = new PHP_LexerGenerator_ParseryyToken(P->string . B->string, array(
        'pattern' => P['pattern'] . B['pattern']));
}
basic_pattern(A) ::= basic_pattern(P) conditional(B). {
    A = new PHP_LexerGenerator_ParseryyToken(P->string . B->string, array(
        'pattern' => P['pattern'] . B['pattern']));
}

character_class(A) ::= OPENCHARCLASS character_class_contents(B) CLOSECHARCLASS. {
    A = new PHP_LexerGenerator_ParseryyToken('[' . B->string . ']', array(
        'pattern' => '[' . B['pattern'] . ']'));
}
character_class(A) ::= OPENCHARCLASS NEGATE character_class_contents(B) CLOSECHARCLASS. {
    A = new PHP_LexerGenerator_ParseryyToken('[^' . B->string . ']', array(
        'pattern' => '[^' . B['pattern'] . ']'));
}
character_class(A) ::= OPENCHARCLASS character_class_contents(B) CLOSECHARCLASS MULTIPLIER(M). {
    A = new PHP_LexerGenerator_ParseryyToken('[' . B->string . ']' . M, array(
        'pattern' => '[' . B['pattern'] . ']' . M));
}
character_class(A) ::= OPENCHARCLASS NEGATE character_class_contents(B) CLOSECHARCLASS MULTIPLIER(M). {
    A = new PHP_LexerGenerator_ParseryyToken('[^' . B->string . ']' . M, array(
        'pattern' => '[^' . B['pattern'] . ']' . M));
}

character_class_contents(A) ::= TEXT(B). {
    A = new PHP_LexerGenerator_ParseryyToken(B, array(
        'pattern' => B));
}
character_class_contents(A) ::= ESCAPEDBACKSLASH(B). {
    A = new PHP_LexerGenerator_ParseryyToken('\\\\' . B, array(
        'pattern' => B));
}
character_class_contents(A) ::= ESCAPEDBACKSLASH(B) HYPHEN TEXT(C). {
    A = new PHP_LexerGenerator_ParseryyToken('\\\\' . B . '-' . C, array(
        'pattern' => B . '-' . C));
}
character_class_contents(A) ::= TEXT(B) HYPHEN TEXT(C). {
    A = new PHP_LexerGenerator_ParseryyToken(B . '-' . C, array(
        'pattern' => B . '-' . C));
}
character_class_contents(A) ::= TEXT(B) HYPHEN ESCAPEDBACKSLASH(C). {
    A = new PHP_LexerGenerator_ParseryyToken(B . '-\\\\' . C, array(
        'pattern' => B . '-' . C));
}
character_class_contents(A) ::= BACKREFERENCE(B). {
    if (((int) substr(B, 1)) > $this->_subpatterns) {
        throw new PHP_LexerGenerator_Exception('Back-reference refers to non-existent ' .
            'sub-pattern ' . substr(B, 1));
    }
    B = substr(B, 1);
    // adjust back-reference for containing ()
    A = new PHP_LexerGenerator_ParseryyToken('\\\\' . (B + $this->_patternIndex), array(
        'pattern' => '\\' . ($this->_updatePattern ? (B + $this->_patternIndex) : B)));
}
character_class_contents(A) ::= COULDBEBACKREF(B). {
    if (((int) substr(B, 1)) > $this->_subpatterns) {
        throw new PHP_LexerGenerator_Exception(B . ' will be interpreted as an invalid' .
            ' back-reference, use "\\0' . substr(B, 1) . ' for octal');
    }
    B = substr(B, 1);
    A = new PHP_LexerGenerator_ParseryyToken('\\\\' . (B + $this->_patternIndex), array(
        'pattern' => '\\' . ($this->_updatePattern ? (B + $this->_patternIndex) : B)));
}
character_class_contents(A) ::= character_class_contents(D) CONTROLCHAR(B). {
    A = new PHP_LexerGenerator_ParseryyToken(D->string . '\\' . B, array(
        'pattern' => D['pattern'] . B));
}
character_class_contents(A) ::= character_class_contents(D) ESCAPEDBACKSLASH(B). {
    A = new PHP_LexerGenerator_ParseryyToken(D->string . '\\\\' . B, array(
        'pattern' => D['pattern'] . B));
}
character_class_contents(A) ::= character_class_contents(D) TEXT(B). {
    A = new PHP_LexerGenerator_ParseryyToken(D->string . B, array(
        'pattern' => D['pattern'] . B));
}
character_class_contents(A) ::= character_class_contents(D) ESCAPEDBACKSLASH(B) HYPHEN CONTROLCHAR(C). {
    A = new PHP_LexerGenerator_ParseryyToken(D->string . '\\\\' . B . '-\\' . C, array(
        'pattern' => D['pattern'] . B . '-' . C));
}
character_class_contents(A) ::= character_class_contents(D) ESCAPEDBACKSLASH(B) HYPHEN TEXT(C). {
    A = new PHP_LexerGenerator_ParseryyToken(D->string . '\\\\' . B . '-' . C, array(
        'pattern' => D['pattern'] . B . '-' . C));
}
character_class_contents(A) ::= character_class_contents(D) TEXT(B) HYPHEN ESCAPEDBACKSLASH(C). {
    A = new PHP_LexerGenerator_ParseryyToken(D->string . B . '-\\\\' . C, array(
        'pattern' => D['pattern'] . B . '-' . C));
}
character_class_contents(A) ::= character_class_contents(D) TEXT(B) HYPHEN TEXT(C). {
    A = new PHP_LexerGenerator_ParseryyToken(D->string . B . '-' . C, array(
        'pattern' => D['pattern'] . B . '-' . C));
}
character_class_contents(A) ::= character_class_contents(P) BACKREFERENCE(B). {
    if (((int) substr(B, 1)) > $this->_subpatterns) {
        throw new PHP_LexerGenerator_Exception('Back-reference refers to non-existent ' .
            'sub-pattern ' . substr(B, 1));
    }
    B = substr(B, 1);
    A = new PHP_LexerGenerator_ParseryyToken(P->string . '\\\\' . (B + $this->_patternIndex), array(
        'pattern' => P['pattern'] . '\\' . ($this->_updatePattern ? (B + $this->_patternIndex) : B)));
}
character_class_contents(A) ::= character_class_contents(P) COULDBEBACKREF(B). {
    if (((int) substr(B, 1)) > $this->_subpatterns) {
        throw new PHP_LexerGenerator_Exception(B . ' will be interpreted as an invalid' .
            ' back-reference, use "\\0' . substr(B, 1) . ' for octal');
    }
    B = substr(B, 1);
    A = new PHP_LexerGenerator_ParseryyToken(P->string . '\\\\' . (B + $this->_patternIndex), array(
        'pattern' => P['pattern'] . '\\' . ($this->_updatePattern ? (B + $this->_patternIndex) : B)));
}

basic_text(A) ::= TEXT(B). {
    A = new PHP_LexerGenerator_ParseryyToken(B, array(
        'pattern' => B));
}
basic_text(A) ::= TEXT(B) MULTIPLIER(M). {
    A = new PHP_LexerGenerator_ParseryyToken(B . M, array(
        'pattern' => B . M));
}
basic_text(A) ::= FULLSTOP(B). {
    A = new PHP_LexerGenerator_ParseryyToken(B, array(
        'pattern' => B));
}
basic_text(A) ::= FULLSTOP(B) MULTIPLIER(M). {
    A = new PHP_LexerGenerator_ParseryyToken(B . M, array(
        'pattern' => B . M));
}
basic_text(A) ::= CONTROLCHAR(B). {
    A = new PHP_LexerGenerator_ParseryyToken('\\' . B, array(
        'pattern' => B));
}
basic_text(A) ::= CONTROLCHAR(B) MULTIPLIER(M). {
    A = new PHP_LexerGenerator_ParseryyToken('\\' . B . M, array(
        'pattern' => B . M));
}
basic_text(A) ::= ESCAPEDBACKSLASH(B). {
    A = new PHP_LexerGenerator_ParseryyToken('\\\\' . B, array(
        'pattern' => B));
}
basic_text(A) ::= ESCAPEDBACKSLASH(B) MULTIPLIER(M). {
    A = new PHP_LexerGenerator_ParseryyToken('\\\\' . B . M, array(
        'pattern' => B . M));
}
basic_text(A) ::= BACKREFERENCE(B). {
    if (((int) substr(B, 1)) > $this->_subpatterns) {
        throw new PHP_LexerGenerator_Exception('Back-reference refers to non-existent ' .
            'sub-pattern ' . substr(B, 1));
    }
    B = substr(B, 1);
    // adjust back-reference for containing ()
    A = new PHP_LexerGenerator_ParseryyToken('\\\\' . (B + $this->_patternIndex), array(
        'pattern' => '\\' . ($this->_updatePattern ? (B + $this->_patternIndex) : B)));
}
basic_text(A) ::= BACKREFERENCE(B) MULTIPLIER(M). {
    if (((int) substr(B, 1)) > $this->_subpatterns) {
        throw new PHP_LexerGenerator_Exception('Back-reference refers to non-existent ' .
            'sub-pattern ' . substr(B, 1));
    }
    B = substr(B, 1);
    // adjust back-reference for containing ()
    A = new PHP_LexerGenerator_ParseryyToken('\\\\' . (B + $this->_patternIndex) . M, array(
        'pattern' => '\\' . ($this->_updatePattern ? (B + $this->_patternIndex) : B) . M));
}
basic_text(A) ::= COULDBEBACKREF(B). {
    if (((int) substr(B, 1)) > $this->_subpatterns) {
        throw new PHP_LexerGenerator_Exception(B . ' will be interpreted as an invalid' .
            ' back-reference, use "\\0' . substr(B, 1) . ' for octal');
    }
    B = substr(B, 1);
    A = new PHP_LexerGenerator_ParseryyToken('\\\\' . (B + $this->_patternIndex), array(
        'pattern' => '\\' . ($this->_updatePattern ? (B + $this->_patternIndex) : B)));
}
basic_text(A) ::= COULDBEBACKREF(B) MULTIPLIER(M). {
    if (((int) substr(B, 1)) > $this->_subpatterns) {
        throw new PHP_LexerGenerator_Exception(B . ' will be interpreted as an invalid' .
            ' back-reference, use "\\0' . substr(B, 1) . ' for octal');
    }
    B = substr(B, 1);
    A = new PHP_LexerGenerator_ParseryyToken('\\\\' . (B + $this->_patternIndex) . M, array(
        'pattern' => '\\' . ($this->_updatePattern ? (B + $this->_patternIndex) : B) . M));
}
basic_text(A) ::= basic_text(T) TEXT(B). {
    A = new PHP_LexerGenerator_ParseryyToken(T->string . B, array(
        'pattern' => T['pattern'] . B));
}
basic_text(A) ::= basic_text(T) TEXT(B) MULTIPLIER(M). {
    A = new PHP_LexerGenerator_ParseryyToken(T->string . B . M, array(
        'pattern' => T['pattern'] . B . M));
}
basic_text(A) ::= basic_text(T) FULLSTOP(B). {
    A = new PHP_LexerGenerator_ParseryyToken(T->string . B, array(
        'pattern' => T['pattern'] . B));
}
basic_text(A) ::= basic_text(T) FULLSTOP(B) MULTIPLIER(M). {
    A = new PHP_LexerGenerator_ParseryyToken(T->string . B . M, array(
        'pattern' => T['pattern'] . B . M));
}
basic_text(A) ::= basic_text(T) CONTROLCHAR(B). {
    A = new PHP_LexerGenerator_ParseryyToken(T->string . '\\' . B, array(
        'pattern' => T['pattern'] . B));
}
basic_text(A) ::= basic_text(T) CONTROLCHAR(B) MULTIPLIER(M). {
    A = new PHP_LexerGenerator_ParseryyToken(T->string . '\\' . B . M, array(
        'pattern' => T['pattern'] . B . M));
}
basic_text(A) ::= basic_text(T) ESCAPEDBACKSLASH(B). {
    A = new PHP_LexerGenerator_ParseryyToken(T->string . '\\\\' . B, array(
        'pattern' => T['pattern'] . B));
}
basic_text(A) ::= basic_text(T) ESCAPEDBACKSLASH(B) MULTIPLIER(M). {
    A = new PHP_LexerGenerator_ParseryyToken(T->string . '\\\\' . B . M, array(
        'pattern' => T['pattern'] . B . M));
}
basic_text(A) ::= basic_text(P) BACKREFERENCE(B). {
    if (((int) substr(B, 1)) > $this->_subpatterns) {
        throw new PHP_LexerGenerator_Exception('Back-reference refers to non-existent ' .
            'sub-pattern ' . substr(B, 1));
    }
    B = substr(B, 1);
    A = new PHP_LexerGenerator_ParseryyToken(P->string . '\\\\' . (B + $this->_patternIndex), array(
        'pattern' => P['pattern'] . '\\' . ($this->_updatePattern ? (B + $this->_patternIndex) : B)));
}
basic_text(A) ::= basic_text(P) BACKREFERENCE(B) MULTIPLIER(M). {
    if (((int) substr(B, 1)) > $this->_subpatterns) {
        throw new PHP_LexerGenerator_Exception('Back-reference refers to non-existent ' .
            'sub-pattern ' . substr(B, 1));
    }
    B = substr(B, 1);
    A = new PHP_LexerGenerator_ParseryyToken(P->string . '\\\\' . (B + $this->_patternIndex) . M, array(
        'pattern' => P['pattern'] . '\\' . ($this->_updatePattern ? (B + $this->_patternIndex) : B) . M));
}
basic_text(A) ::= basic_text(P) COULDBEBACKREF(B). {
    if (((int) substr(B, 1)) > $this->_subpatterns) {
        throw new PHP_LexerGenerator_Exception(B . ' will be interpreted as an invalid' .
            ' back-reference, use "\\0' . substr(B, 1) . ' for octal');
    }
    B = substr(B, 1);
    A = new PHP_LexerGenerator_ParseryyToken(P->string . '\\\\' . (B + $this->_patternIndex), array(
        'pattern' => P['pattern'] . '\\' . ($this->_updatePattern ? (B + $this->_patternIndex) : B)));
}
basic_text(A) ::= basic_text(P) COULDBEBACKREF(B) MULTIPLIER(M). {
    if (((int) substr(B, 1)) > $this->_subpatterns) {
        throw new PHP_LexerGenerator_Exception(B . ' will be interpreted as an invalid' .
            ' back-reference, use "\\0' . substr(B, 1) . ' for octal');
    }
    B = substr(B, 1);
    A = new PHP_LexerGenerator_ParseryyToken(P->string . '\\\\' . (B + $this->_patternIndex) . M, array(
        'pattern' => P['pattern'] . '\\' . ($this->_updatePattern ? (B + $this->_patternIndex) : B) . M));
}

assertion ::= OPENASSERTION(B) INTERNALOPTIONS(C) CLOSEPAREN(D). {
    throw new PHP_LexerGenerator_Exception('Error: cannot set preg options directly with "' .
        B . C . D . '"');
}
assertion ::= OPENASSERTION(B) INTERNALOPTIONS(C) COLON(D) pattern(E) CLOSEPAREN(F). {
    throw new PHP_LexerGenerator_Exception('Error: cannot set preg options directly with "' .
        B . C . D . E['pattern'] . F . '"');
}

grouping(A) ::= OPENASSERTION COLON pattern(B) CLOSEPAREN. {
    A = new PHP_LexerGenerator_ParseryyToken('(?:' . B->string . ')', array(
        'pattern' => '(?:' . B['pattern'] . ')'));
}
grouping(A) ::= OPENASSERTION COLON pattern(B) CLOSEPAREN MULTIPLIER(M). {
    A = new PHP_LexerGenerator_ParseryyToken('(?:' . B->string . ')' . M, array(
        'pattern' => '(?:' . B['pattern'] . ')' . M));
}

conditional(A) ::= OPENASSERTION OPENPAREN TEXT(T) CLOSEPAREN pattern(B) CLOSEPAREN MULTIPLIER(M). {
    if (T != 'R') {
        if (!preg_match('/[1-9][0-9]*/', T)) {
            throw new PHP_LexerGenerator_Exception('Invalid sub-pattern conditional: "(?(' . T . ')"');
        }
        if (T > $this->_subpatterns) {
            throw new PHP_LexerGenerator_Exception('sub-pattern conditional . "' . T . '" refers to non-existent sub-pattern');
        }
    } else {
        throw new PHP_LexerGenerator_Exception('Recursive conditional (?(' . T . ')" cannot work in this lexer');
    }
    A = new PHP_LexerGenerator_ParseryyToken('(?(' . T . ')' . B->string . ')' . M, array(
        'pattern' => '(?(' . T . ')' . B['pattern'] . ')' . M));
}
conditional(A) ::= OPENASSERTION OPENPAREN TEXT(T) CLOSEPAREN pattern(B) CLOSEPAREN. {
    if (T != 'R') {
        if (!preg_match('/[1-9][0-9]*/', T)) {
            throw new PHP_LexerGenerator_Exception('Invalid sub-pattern conditional: "(?(' . T . ')"');
        }
        if (T > $this->_subpatterns) {
            throw new PHP_LexerGenerator_Exception('sub-pattern conditional . "' . T . '" refers to non-existent sub-pattern');
        }
    } else {
        throw new PHP_LexerGenerator_Exception('Recursive conditional (?(' . T . ')" cannot work in this lexer');
    }
    A = new PHP_LexerGenerator_ParseryyToken('(?(' . T . ')' . B->string . ')', array(
        'pattern' => '(?(' . T . ')' . B['pattern'] . ')'));
}
conditional(A) ::= OPENASSERTION lookahead(B) pattern(C) CLOSEPAREN. {
    A = new PHP_LexerGenerator_ParseryyToken('(?' . B->string . C->string . ')', array(
        'pattern' => '(?' . B['pattern'] . C['pattern'] . ')'));
}
conditional(A) ::= OPENASSERTION lookahead(B) pattern(C) CLOSEPAREN MULTIPLIER(M). {
    A = new PHP_LexerGenerator_ParseryyToken('(?' . B->string . C->string . ')' . M, array(
        'pattern' => '(?' . B['pattern'] . C['pattern'] . ')' . M));
}
conditional ::= OPENASSERTION lookbehind pattern(B) CLOSEPAREN. {
    throw new PHP_LexerGenerator_Exception('Look-behind assertions cannot be used: "(?<=' .
        B['pattern'] . ')');
}
conditional ::= OPENASSERTION lookbehind pattern(B) CLOSEPAREN MULTIPLIER. {
    throw new PHP_LexerGenerator_Exception('Look-behind assertions cannot be used: "(?<=' .
        B['pattern'] . ')');
}

lookahead(A) ::= OPENASSERTION POSITIVELOOKAHEAD pattern(B) CLOSEPAREN. {
    A = new PHP_LexerGenerator_ParseryyToken('(?=' . B->string . ')', array(
        'pattern '=> '(?=' . B['pattern'] . ')'));
}
lookahead(A) ::= OPENASSERTION NEGATIVELOOKAHEAD pattern(B) CLOSEPAREN. {
    A = new PHP_LexerGenerator_ParseryyToken('(?!' . B->string . ')', array(
        'pattern' => '(?!' . B['pattern'] . ')'));
}

lookbehind ::= OPENASSERTION POSITIVELOOKBEHIND pattern(B) CLOSEPAREN. {
    throw new PHP_LexerGenerator_Exception('Look-behind assertions cannot be used: "(?<=' .
        B['pattern'] . ')');
}
lookbehind ::= OPENASSERTION NEGATIVELOOKBEHIND pattern(B) CLOSEPAREN. {
    throw new PHP_LexerGenerator_Exception('Look-behind assertions cannot be used: "(?<!' .
        B['pattern'] . ')');
}

subpattern ::= OPENASSERTION PATTERNNAME(B) pattern CLOSEPAREN. {
    throw new PHP_LexerGenerator_Exception('Cannot use named sub-patterns: "(' .
        B['pattern'] . ')');
}
subpattern ::= OPENASSERTION PATTERNNAME(B) pattern CLOSEPAREN MULTIPLIER. {
    throw new PHP_LexerGenerator_Exception('Cannot use named sub-patterns: "(' .
        B['pattern'] . ')');
}
subpattern(A) ::= OPENPAREN pattern(B) CLOSEPAREN. {
    $this->_subpatterns++;
    A = new PHP_LexerGenerator_ParseryyToken('(' . B->string . ')', array(
        'pattern' => '(' . B['pattern'] . ')'));
}
subpattern(A) ::= OPENPAREN pattern(B) CLOSEPAREN MULTIPLIER(M). {
    $this->_subpatterns++;
    A = new PHP_LexerGenerator_ParseryyToken('(' . B->string . ')' . M, array(
        'pattern' => '(' . B['pattern'] . ')' . M));
}

onceonly(A) ::= OPENASSERTION ONCEONLY pattern(B) CLOSEPAREN. {
    A = new PHP_LexerGenerator_ParseryyToken('(?>' . B->string . ')', array(
        'pattern' => '(?>' . B['pattern'] . ')'));
}

comment(A) ::= OPENASSERTION COMMENT(B) CLOSEPAREN. {
    A = new PHP_LexerGenerator_ParseryyToken('(' . B->string . ')', array(
        'pattern' => '(' . B['pattern'] . ')'));
}

recur ::= OPENASSERTION RECUR CLOSEPAREN. {
    throw new Exception('(?R) cannot work in this lexer');
}
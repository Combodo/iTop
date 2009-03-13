<?php
/* Driver template for the PHP_OQLParser_rGenerator parser generator. (PHP port of LEMON)
*/

/**
 * This can be used to store both the string representation of
 * a token, and any useful meta-data associated with the token.
 *
 * meta-data should be stored as an array
 */
class OQLParser_yyToken implements ArrayAccess
{
    public $string = '';
    public $metadata = array();

    function __construct($s, $m = array())
    {
        if ($s instanceof OQLParser_yyToken) {
            $this->string = $s->string;
            $this->metadata = $s->metadata;
        } else {
            $this->string = (string) $s;
            if ($m instanceof OQLParser_yyToken) {
                $this->metadata = $m->metadata;
            } elseif (is_array($m)) {
                $this->metadata = $m;
            }
        }
    }

    function __toString()
    {
        return $this->_string;
    }

    function offsetExists($offset)
    {
        return isset($this->metadata[$offset]);
    }

    function offsetGet($offset)
    {
        return $this->metadata[$offset];
    }

    function offsetSet($offset, $value)
    {
        if ($offset === null) {
            if (isset($value[0])) {
                $x = ($value instanceof OQLParser_yyToken) ?
                    $value->metadata : $value;
                $this->metadata = array_merge($this->metadata, $x);
                return;
            }
            $offset = count($this->metadata);
        }
        if ($value === null) {
            return;
        }
        if ($value instanceof OQLParser_yyToken) {
            if ($value->metadata) {
                $this->metadata[$offset] = $value->metadata;
            }
        } elseif ($value) {
            $this->metadata[$offset] = $value;
        }
    }

    function offsetUnset($offset)
    {
        unset($this->metadata[$offset]);
    }
}

/** The following structure represents a single element of the
 * parser's stack.  Information stored includes:
 *
 *   +  The state number for the parser at this level of the stack.
 *
 *   +  The value of the token stored at this level of the stack.
 *      (In other words, the "major" token.)
 *
 *   +  The semantic value stored at this level of the stack.  This is
 *      the information used by the action routines in the grammar.
 *      It is sometimes called the "minor" token.
 */
class OQLParser_yyStackEntry
{
    public $stateno;       /* The state-number */
    public $major;         /* The major token value.  This is the code
                     ** number for the token at this stack level */
    public $minor; /* The user-supplied minor token value.  This
                     ** is the value of the token  */
};

// code external to the class is included here

// declare_class is output here
#line 2 "oql-parser.y"
class OQLParserRaw#line 102 "oql-parser.php"
{
/* First off, code is included which follows the "include_class" declaration
** in the input file. */

/* Next is all token values, as class constants
*/
/* 
** These constants (all generated automatically by the parser generator)
** specify the various kinds of tokens (terminals) that the parser
** understands. 
**
** Each symbol here is a terminal symbol in the grammar.
*/
    const SELECT                         =  1;
    const AS_ALIAS                       =  2;
    const WHERE                          =  3;
    const JOIN                           =  4;
    const ON                             =  5;
    const EQ                             =  6;
    const PAR_OPEN                       =  7;
    const PAR_CLOSE                      =  8;
    const COMA                           =  9;
    const INTERVAL                       = 10;
    const F_DAY                          = 11;
    const F_MONTH                        = 12;
    const F_YEAR                         = 13;
    const DOT                            = 14;
    const NAME                           = 15;
    const NUMVAL                         = 16;
    const STRVAL                         = 17;
    const NOT_EQ                         = 18;
    const LOG_AND                        = 19;
    const LOG_OR                         = 20;
    const GT                             = 21;
    const LT                             = 22;
    const GE                             = 23;
    const LE                             = 24;
    const MATH_DIV                       = 25;
    const MATH_MULT                      = 26;
    const MATH_PLUS                      = 27;
    const MATH_MINUS                     = 28;
    const LIKE                           = 29;
    const NOT_LIKE                       = 30;
    const IN                             = 31;
    const NOT_IN                         = 32;
    const F_IF                           = 33;
    const F_ELT                          = 34;
    const F_COALESCE                     = 35;
    const F_CONCAT                       = 36;
    const F_SUBSTR                       = 37;
    const F_TRIM                         = 38;
    const F_DATE                         = 39;
    const F_DATE_FORMAT                  = 40;
    const F_CURRENT_DATE                 = 41;
    const F_NOW                          = 42;
    const F_TIME                         = 43;
    const F_TO_DAYS                      = 44;
    const F_FROM_DAYS                    = 45;
    const F_DATE_ADD                     = 46;
    const F_DATE_SUB                     = 47;
    const F_ROUND                        = 48;
    const F_FLOOR                        = 49;
    const YY_NO_ACTION = 186;
    const YY_ACCEPT_ACTION = 185;
    const YY_ERROR_ACTION = 184;

/* Next are that tables used to determine what action to take based on the
** current state and lookahead token.  These tables are used to implement
** functions that take a state number and lookahead value and return an
** action integer.  
**
** Suppose the action integer is N.  Then the action is determined as
** follows
**
**   0 <= N < self::YYNSTATE                              Shift N.  That is,
**                                                        push the lookahead
**                                                        token onto the stack
**                                                        and goto state N.
**
**   self::YYNSTATE <= N < self::YYNSTATE+self::YYNRULE   Reduce by rule N-YYNSTATE.
**
**   N == self::YYNSTATE+self::YYNRULE                    A syntax error has occurred.
**
**   N == self::YYNSTATE+self::YYNRULE+1                  The parser accepts its
**                                                        input. (and concludes parsing)
**
**   N == self::YYNSTATE+self::YYNRULE+2                  No such action.  Denotes unused
**                                                        slots in the yy_action[] table.
**
** The action table is constructed as a single large static array $yy_action.
** Given state S and lookahead X, the action is computed as
**
**      self::$yy_action[self::$yy_shift_ofst[S] + X ]
**
** If the index value self::$yy_shift_ofst[S]+X is out of range or if the value
** self::$yy_lookahead[self::$yy_shift_ofst[S]+X] is not equal to X or if
** self::$yy_shift_ofst[S] is equal to self::YY_SHIFT_USE_DFLT, it means that
** the action is not in the table and that self::$yy_default[S] should be used instead.  
**
** The formula above is for computing the action when the lookahead is
** a terminal symbol.  If the lookahead is a non-terminal (as occurs after
** a reduce action) then the static $yy_reduce_ofst array is used in place of
** the static $yy_shift_ofst array and self::YY_REDUCE_USE_DFLT is used in place of
** self::YY_SHIFT_USE_DFLT.
**
** The following are the tables generated in this section:
**
**  self::$yy_action        A single table containing all actions.
**  self::$yy_lookahead     A table containing the lookahead for each entry in
**                          yy_action.  Used to detect hash collisions.
**  self::$yy_shift_ofst    For each state, the offset into self::$yy_action for
**                          shifting terminals.
**  self::$yy_reduce_ofst   For each state, the offset into self::$yy_action for
**                          shifting non-terminals after a reduce.
**  self::$yy_default       Default action for each state.
*/
    const YY_SZ_ACTTAB = 369;
static public $yy_action = array(
 /*     0 */     6,   67,   66,    5,   61,   62,   60,   65,   35,   67,
 /*    10 */    66,   82,   30,   83,   13,   81,   75,   74,   68,   81,
 /*    20 */    75,   74,   68,    8,   29,   20,   42,   44,   41,   43,
 /*    30 */    36,   37,   38,   39,   63,   58,   57,   56,   59,   55,
 /*    40 */    54,   48,   47,   23,   40,   40,   31,   49,    4,    6,
 /*    50 */    27,   52,   14,   61,   62,   60,   94,   35,   67,   66,
 /*    60 */    64,    2,   69,   70,   73,   22,   40,   50,   15,   18,
 /*    70 */    26,   21,   18,   19,   33,   42,   44,   41,   43,   36,
 /*    80 */    37,   38,   39,   63,   58,   57,   56,   59,   55,   54,
 /*    90 */    48,   47,    6,    4,   40,   27,   61,   62,   60,    3,
 /*   100 */    35,   67,   66,   46,   24,    1,   18,   69,   70,   73,
 /*   110 */    80,   25,   16,   19,   35,   77,   17,  158,   42,   44,
 /*   120 */    41,   43,   36,   37,   38,   39,   63,   58,   57,   56,
 /*   130 */    59,   55,   54,   48,   47,   72,   40,  158,  158,  158,
 /*   140 */    93,   92,  105,  158,  158,  158,  158,   71,   84,   85,
 /*   150 */    99,   98,   97,  100,  101,  104,  103,  102,   96,   95,
 /*   160 */    89,   88,   72,  158,   79,  158,  158,  158,  158,  158,
 /*   170 */   158,  158,  158,  158,   71,   84,   85,   99,   98,   97,
 /*   180 */   100,  101,  104,  103,  102,   96,   95,   89,   88,   72,
 /*   190 */   158,  158,  158,  158,  158,  158,  158,  158,  158,  158,
 /*   200 */   158,   71,   84,   85,   99,   98,   97,  100,  101,  104,
 /*   210 */   103,  102,   96,   95,   89,   88,  185,   90,   78,   31,
 /*   220 */   158,  158,  158,  158,   86,   12,  158,   87,  158,   31,
 /*   230 */    32,  158,  158,   53,   34,   81,   75,   74,   68,   40,
 /*   240 */    31,  158,  158,  158,  158,   86,   11,  158,   87,   40,
 /*   250 */   158,   32,   28,  158,   45,  158,   81,   75,   74,   68,
 /*   260 */    40,   31,  158,  158,  158,  158,   86,   11,  158,   87,
 /*   270 */   158,  158,   32,  158,  158,   91,  158,   81,   75,   74,
 /*   280 */    68,   40,  158,  158,   76,   31,  158,  158,  158,  158,
 /*   290 */    86,   12,  158,   87,  158,   31,   32,  158,  158,   51,
 /*   300 */    34,   81,   75,   74,   68,   40,   31,  158,  158,  158,
 /*   310 */   158,   86,    9,  158,   87,   40,  158,   32,  158,  158,
 /*   320 */   158,  158,   81,   75,   74,   68,   40,   31,  158,  158,
 /*   330 */   158,  158,   86,   10,  158,   87,  158,  158,   32,  158,
 /*   340 */   158,  158,  158,   81,   75,   74,   68,   40,   31,  158,
 /*   350 */   158,  158,  158,   86,    7,  158,   87,  158,  158,   32,
 /*   360 */   158,  158,  158,  158,   81,   75,   74,   68,   40,
    );
    static public $yy_lookahead = array(
 /*     0 */     7,   16,   17,   10,   11,   12,   13,   62,   15,   16,
 /*    10 */    17,   62,   67,    8,    9,   70,   71,   72,   73,   70,
 /*    20 */    71,   72,   73,    7,   54,   54,   33,   34,   35,   36,
 /*    30 */    37,   38,   39,   40,   41,   42,   43,   44,   45,   46,
 /*    40 */    47,   48,   49,    1,   74,   74,   54,   56,   61,    7,
 /*    50 */    63,   59,    5,   11,   12,   13,   69,   15,   16,   17,
 /*    60 */     8,    9,   75,   76,   77,    2,   74,   55,    5,   57,
 /*    70 */    55,    2,   57,    4,   54,   33,   34,   35,   36,   37,
 /*    80 */    38,   39,   40,   41,   42,   43,   44,   45,   46,   47,
 /*    90 */    48,   49,    7,   61,   74,   63,   11,   12,   13,    3,
 /*   100 */    15,   16,   17,   74,   55,    7,   57,   75,   76,   77,
 /*   110 */    64,   14,    6,    4,   15,   56,   54,   78,   33,   34,
 /*   120 */    35,   36,   37,   38,   39,   40,   41,   42,   43,   44,
 /*   130 */    45,   46,   47,   48,   49,    6,   74,   78,   78,   78,
 /*   140 */    11,   12,   13,   78,   78,   78,   78,   18,   19,   20,
 /*   150 */    21,   22,   23,   24,   25,   26,   27,   28,   29,   30,
 /*   160 */    31,   32,    6,   78,    8,   78,   78,   78,   78,   78,
 /*   170 */    78,   78,   78,   78,   18,   19,   20,   21,   22,   23,
 /*   180 */    24,   25,   26,   27,   28,   29,   30,   31,   32,    6,
 /*   190 */    78,   78,   78,   78,   78,   78,   78,   78,   78,   78,
 /*   200 */    78,   18,   19,   20,   21,   22,   23,   24,   25,   26,
 /*   210 */    27,   28,   29,   30,   31,   32,   51,   52,   53,   54,
 /*   220 */    78,   78,   78,   78,   59,   60,   78,   62,   78,   54,
 /*   230 */    65,   78,   78,   58,   59,   70,   71,   72,   73,   74,
 /*   240 */    54,   78,   78,   78,   78,   59,   60,   78,   62,   74,
 /*   250 */    78,   65,   66,   78,   68,   78,   70,   71,   72,   73,
 /*   260 */    74,   54,   78,   78,   78,   78,   59,   60,   78,   62,
 /*   270 */    78,   78,   65,   78,   78,   68,   78,   70,   71,   72,
 /*   280 */    73,   74,   78,   78,   53,   54,   78,   78,   78,   78,
 /*   290 */    59,   60,   78,   62,   78,   54,   65,   78,   78,   58,
 /*   300 */    59,   70,   71,   72,   73,   74,   54,   78,   78,   78,
 /*   310 */    78,   59,   60,   78,   62,   74,   78,   65,   78,   78,
 /*   320 */    78,   78,   70,   71,   72,   73,   74,   54,   78,   78,
 /*   330 */    78,   78,   59,   60,   78,   62,   78,   78,   65,   78,
 /*   340 */    78,   78,   78,   70,   71,   72,   73,   74,   54,   78,
 /*   350 */    78,   78,   78,   59,   60,   78,   62,   78,   78,   65,
 /*   360 */    78,   78,   78,   78,   70,   71,   72,   73,   74,
);
    const YY_SHIFT_USE_DFLT = -16;
    const YY_SHIFT_MAX = 34;
    static public $yy_shift_ofst = array(
 /*     0 */    42,   -7,   -7,   85,   85,   85,   85,  129,  -15,  156,
 /*    10 */   183,  183,  183,  -15,   99,   99,   99,   69,  109,   99,
 /*    20 */   109,   99,   99,   99,   96,   99,   96,   16,   52,   63,
 /*    30 */     5,   97,   98,   47,  106,
);
    const YY_REDUCE_USE_DFLT = -56;
    const YY_REDUCE_MAX = 27;
    static public $yy_reduce_ofst = array(
 /*     0 */   165,  186,  207,  231,  273,  294,  252,  -13,  -55,   32,
 /*    10 */    32,   32,   32,  -51,  175,  241,   -8,   49,   12,  -30,
 /*    20 */    15,  -29,   20,   62,   59,   29,   -9,   46,
);
    static public $yyExpectedTokens = array(
        /* 0 */ array(1, 7, 11, 12, 13, 15, 16, 17, 33, 34, 35, 36, 37, 38, 39, 40, 41, 42, 43, 44, 45, 46, 47, 48, 49, ),
        /* 1 */ array(7, 10, 11, 12, 13, 15, 16, 17, 33, 34, 35, 36, 37, 38, 39, 40, 41, 42, 43, 44, 45, 46, 47, 48, 49, ),
        /* 2 */ array(7, 10, 11, 12, 13, 15, 16, 17, 33, 34, 35, 36, 37, 38, 39, 40, 41, 42, 43, 44, 45, 46, 47, 48, 49, ),
        /* 3 */ array(7, 11, 12, 13, 15, 16, 17, 33, 34, 35, 36, 37, 38, 39, 40, 41, 42, 43, 44, 45, 46, 47, 48, 49, ),
        /* 4 */ array(7, 11, 12, 13, 15, 16, 17, 33, 34, 35, 36, 37, 38, 39, 40, 41, 42, 43, 44, 45, 46, 47, 48, 49, ),
        /* 5 */ array(7, 11, 12, 13, 15, 16, 17, 33, 34, 35, 36, 37, 38, 39, 40, 41, 42, 43, 44, 45, 46, 47, 48, 49, ),
        /* 6 */ array(7, 11, 12, 13, 15, 16, 17, 33, 34, 35, 36, 37, 38, 39, 40, 41, 42, 43, 44, 45, 46, 47, 48, 49, ),
        /* 7 */ array(6, 11, 12, 13, 18, 19, 20, 21, 22, 23, 24, 25, 26, 27, 28, 29, 30, 31, 32, ),
        /* 8 */ array(16, 17, ),
        /* 9 */ array(6, 8, 18, 19, 20, 21, 22, 23, 24, 25, 26, 27, 28, 29, 30, 31, 32, ),
        /* 10 */ array(6, 18, 19, 20, 21, 22, 23, 24, 25, 26, 27, 28, 29, 30, 31, 32, ),
        /* 11 */ array(6, 18, 19, 20, 21, 22, 23, 24, 25, 26, 27, 28, 29, 30, 31, 32, ),
        /* 12 */ array(6, 18, 19, 20, 21, 22, 23, 24, 25, 26, 27, 28, 29, 30, 31, 32, ),
        /* 13 */ array(16, 17, ),
        /* 14 */ array(15, ),
        /* 15 */ array(15, ),
        /* 16 */ array(15, ),
        /* 17 */ array(2, 4, ),
        /* 18 */ array(4, ),
        /* 19 */ array(15, ),
        /* 20 */ array(4, ),
        /* 21 */ array(15, ),
        /* 22 */ array(15, ),
        /* 23 */ array(15, ),
        /* 24 */ array(3, ),
        /* 25 */ array(15, ),
        /* 26 */ array(3, ),
        /* 27 */ array(7, ),
        /* 28 */ array(8, 9, ),
        /* 29 */ array(2, 5, ),
        /* 30 */ array(8, 9, ),
        /* 31 */ array(14, ),
        /* 32 */ array(7, ),
        /* 33 */ array(5, ),
        /* 34 */ array(6, ),
        /* 35 */ array(),
        /* 36 */ array(),
        /* 37 */ array(),
        /* 38 */ array(),
        /* 39 */ array(),
        /* 40 */ array(),
        /* 41 */ array(),
        /* 42 */ array(),
        /* 43 */ array(),
        /* 44 */ array(),
        /* 45 */ array(),
        /* 46 */ array(),
        /* 47 */ array(),
        /* 48 */ array(),
        /* 49 */ array(),
        /* 50 */ array(),
        /* 51 */ array(),
        /* 52 */ array(),
        /* 53 */ array(),
        /* 54 */ array(),
        /* 55 */ array(),
        /* 56 */ array(),
        /* 57 */ array(),
        /* 58 */ array(),
        /* 59 */ array(),
        /* 60 */ array(),
        /* 61 */ array(),
        /* 62 */ array(),
        /* 63 */ array(),
        /* 64 */ array(),
        /* 65 */ array(),
        /* 66 */ array(),
        /* 67 */ array(),
        /* 68 */ array(),
        /* 69 */ array(),
        /* 70 */ array(),
        /* 71 */ array(),
        /* 72 */ array(),
        /* 73 */ array(),
        /* 74 */ array(),
        /* 75 */ array(),
        /* 76 */ array(),
        /* 77 */ array(),
        /* 78 */ array(),
        /* 79 */ array(),
        /* 80 */ array(),
        /* 81 */ array(),
        /* 82 */ array(),
        /* 83 */ array(),
        /* 84 */ array(),
        /* 85 */ array(),
        /* 86 */ array(),
        /* 87 */ array(),
        /* 88 */ array(),
        /* 89 */ array(),
        /* 90 */ array(),
        /* 91 */ array(),
        /* 92 */ array(),
        /* 93 */ array(),
        /* 94 */ array(),
        /* 95 */ array(),
        /* 96 */ array(),
        /* 97 */ array(),
        /* 98 */ array(),
        /* 99 */ array(),
        /* 100 */ array(),
        /* 101 */ array(),
        /* 102 */ array(),
        /* 103 */ array(),
        /* 104 */ array(),
        /* 105 */ array(),
);
    static public $yy_default = array(
 /*     0 */   184,  128,  184,  184,  184,  184,  184,  184,  184,  184,
 /*    10 */   120,  131,  118,  184,  184,  184,  184,  114,  113,  184,
 /*    20 */   114,  184,  184,  184,  111,  184,  111,  184,  184,  184,
 /*    30 */   184,  184,  184,  184,  184,  142,  168,  169,  170,  171,
 /*    40 */   141,  166,  164,  167,  165,  129,  140,  183,  182,  109,
 /*    50 */   112,  116,  117,  115,  181,  180,  175,  174,  173,  176,
 /*    60 */   177,  179,  178,  172,  124,  126,  144,  143,  139,  145,
 /*    70 */   146,  149,  148,  147,  138,  137,  110,  108,  107,  119,
 /*    80 */   123,  136,  127,  125,  150,  151,  122,  121,  163,  162,
 /*    90 */   106,  130,  134,  133,  132,  161,  160,  154,  153,  152,
 /*   100 */   155,  156,  159,  158,  157,  135,
);
/* The next thing included is series of defines which control
** various aspects of the generated parser.
**    self::YYNOCODE      is a number which corresponds
**                        to no legal terminal or nonterminal number.  This
**                        number is used to fill in empty slots of the hash 
**                        table.
**    self::YYFALLBACK    If defined, this indicates that one or more tokens
**                        have fall-back values which should be used if the
**                        original value of the token will not parse.
**    self::YYSTACKDEPTH  is the maximum depth of the parser's stack.
**    self::YYNSTATE      the combined number of states.
**    self::YYNRULE       the number of rules in the grammar
**    self::YYERRORSYMBOL is the code number of the error symbol.  If not
**                        defined, then do no error processing.
*/
    const YYNOCODE = 79;
    const YYSTACKDEPTH = 100;
    const YYNSTATE = 106;
    const YYNRULE = 78;
    const YYERRORSYMBOL = 50;
    const YYERRSYMDT = 'yy0';
    const YYFALLBACK = 0;
    /** The next table maps tokens into fallback tokens.  If a construct
     * like the following:
     * 
     *      %fallback ID X Y Z.
     *
     * appears in the grammer, then ID becomes a fallback token for X, Y,
     * and Z.  Whenever one of the tokens X, Y, or Z is input to the parser
     * but it does not parse, the type of the token is changed to ID and
     * the parse is retried before an error is thrown.
     */
    static public $yyFallback = array(
    );
    /**
     * Turn parser tracing on by giving a stream to which to write the trace
     * and a prompt to preface each trace message.  Tracing is turned off
     * by making either argument NULL 
     *
     * Inputs:
     * 
     * - A stream resource to which trace output should be written.
     *   If NULL, then tracing is turned off.
     * - A prefix string written at the beginning of every
     *   line of trace output.  If NULL, then tracing is
     *   turned off.
     *
     * Outputs:
     * 
     * - None.
     * @param resource
     * @param string
     */
    static function Trace($TraceFILE, $zTracePrompt)
    {
        if (!$TraceFILE) {
            $zTracePrompt = 0;
        } elseif (!$zTracePrompt) {
            $TraceFILE = 0;
        }
        self::$yyTraceFILE = $TraceFILE;
        self::$yyTracePrompt = $zTracePrompt;
    }

    /**
     * Output debug information to output (php://output stream)
     */
    static function PrintTrace()
    {
        self::$yyTraceFILE = fopen('php://output', 'w');
        self::$yyTracePrompt = '';
    }

    /**
     * @var resource|0
     */
    static public $yyTraceFILE;
    /**
     * String to prepend to debug output
     * @var string|0
     */
    static public $yyTracePrompt;
    /**
     * @var int
     */
    public $yyidx;                    /* Index of top element in stack */
    /**
     * @var int
     */
    public $yyerrcnt;                 /* Shifts left before out of the error */
    /**
     * @var array
     */
    public $yystack = array();  /* The parser's stack */

    /**
     * For tracing shifts, the names of all terminals and nonterminals
     * are required.  The following table supplies these names
     * @var array
     */
    static public $yyTokenName = array( 
  '$',             'SELECT',        'AS_ALIAS',      'WHERE',       
  'JOIN',          'ON',            'EQ',            'PAR_OPEN',    
  'PAR_CLOSE',     'COMA',          'INTERVAL',      'F_DAY',       
  'F_MONTH',       'F_YEAR',        'DOT',           'NAME',        
  'NUMVAL',        'STRVAL',        'NOT_EQ',        'LOG_AND',     
  'LOG_OR',        'GT',            'LT',            'GE',          
  'LE',            'MATH_DIV',      'MATH_MULT',     'MATH_PLUS',   
  'MATH_MINUS',    'LIKE',          'NOT_LIKE',      'IN',          
  'NOT_IN',        'F_IF',          'F_ELT',         'F_COALESCE',  
  'F_CONCAT',      'F_SUBSTR',      'F_TRIM',        'F_DATE',      
  'F_DATE_FORMAT',  'F_CURRENT_DATE',  'F_NOW',         'F_TIME',      
  'F_TO_DAYS',     'F_FROM_DAYS',   'F_DATE_ADD',    'F_DATE_SUB',  
  'F_ROUND',       'F_FLOOR',       'error',         'result',      
  'query',         'condition',     'class_name',    'join_statement',
  'where_statement',  'join_item',     'join_condition',  'field_id',    
  'expression',    'operator',      'scalar',        'list_operator',
  'list',          'func_name',     'arg_list',      'scalar_list', 
  'argument',      'interval_unit',  'num_scalar',    'str_scalar',  
  'num_value',     'str_value',     'name',          'log_operator',
  'num_operator',  'str_operator',
    );

    /**
     * For tracing reduce actions, the names of all rules are required.
     * @var array
     */
    static public $yyRuleName = array(
 /*   0 */ "result ::= query",
 /*   1 */ "result ::= condition",
 /*   2 */ "query ::= SELECT class_name join_statement where_statement",
 /*   3 */ "query ::= SELECT class_name AS_ALIAS class_name join_statement where_statement",
 /*   4 */ "where_statement ::= WHERE condition",
 /*   5 */ "where_statement ::=",
 /*   6 */ "join_statement ::= join_item join_statement",
 /*   7 */ "join_statement ::= join_item",
 /*   8 */ "join_statement ::=",
 /*   9 */ "join_item ::= JOIN class_name AS_ALIAS class_name ON join_condition",
 /*  10 */ "join_item ::= JOIN class_name ON join_condition",
 /*  11 */ "join_condition ::= field_id EQ field_id",
 /*  12 */ "condition ::= expression",
 /*  13 */ "expression ::= PAR_OPEN expression PAR_CLOSE",
 /*  14 */ "expression ::= expression operator expression",
 /*  15 */ "expression ::= scalar",
 /*  16 */ "expression ::= field_id",
 /*  17 */ "expression ::= expression list_operator list",
 /*  18 */ "expression ::= func_name PAR_OPEN arg_list PAR_CLOSE",
 /*  19 */ "list ::= PAR_OPEN scalar_list PAR_CLOSE",
 /*  20 */ "scalar_list ::= scalar",
 /*  21 */ "scalar_list ::= scalar_list COMA scalar",
 /*  22 */ "arg_list ::=",
 /*  23 */ "arg_list ::= argument",
 /*  24 */ "arg_list ::= arg_list COMA argument",
 /*  25 */ "argument ::= expression",
 /*  26 */ "argument ::= INTERVAL expression interval_unit",
 /*  27 */ "interval_unit ::= F_DAY",
 /*  28 */ "interval_unit ::= F_MONTH",
 /*  29 */ "interval_unit ::= F_YEAR",
 /*  30 */ "scalar ::= num_scalar",
 /*  31 */ "scalar ::= str_scalar",
 /*  32 */ "num_scalar ::= num_value",
 /*  33 */ "str_scalar ::= str_value",
 /*  34 */ "field_id ::= class_name DOT name",
 /*  35 */ "class_name ::= name",
 /*  36 */ "name ::= NAME",
 /*  37 */ "num_value ::= NUMVAL",
 /*  38 */ "str_value ::= STRVAL",
 /*  39 */ "operator ::= log_operator",
 /*  40 */ "operator ::= num_operator",
 /*  41 */ "operator ::= str_operator",
 /*  42 */ "operator ::= EQ",
 /*  43 */ "operator ::= NOT_EQ",
 /*  44 */ "log_operator ::= LOG_AND",
 /*  45 */ "log_operator ::= LOG_OR",
 /*  46 */ "num_operator ::= GT",
 /*  47 */ "num_operator ::= LT",
 /*  48 */ "num_operator ::= GE",
 /*  49 */ "num_operator ::= LE",
 /*  50 */ "num_operator ::= MATH_DIV",
 /*  51 */ "num_operator ::= MATH_MULT",
 /*  52 */ "num_operator ::= MATH_PLUS",
 /*  53 */ "num_operator ::= MATH_MINUS",
 /*  54 */ "str_operator ::= LIKE",
 /*  55 */ "str_operator ::= NOT_LIKE",
 /*  56 */ "list_operator ::= IN",
 /*  57 */ "list_operator ::= NOT_IN",
 /*  58 */ "func_name ::= F_IF",
 /*  59 */ "func_name ::= F_ELT",
 /*  60 */ "func_name ::= F_COALESCE",
 /*  61 */ "func_name ::= F_CONCAT",
 /*  62 */ "func_name ::= F_SUBSTR",
 /*  63 */ "func_name ::= F_TRIM",
 /*  64 */ "func_name ::= F_DATE",
 /*  65 */ "func_name ::= F_DATE_FORMAT",
 /*  66 */ "func_name ::= F_CURRENT_DATE",
 /*  67 */ "func_name ::= F_NOW",
 /*  68 */ "func_name ::= F_TIME",
 /*  69 */ "func_name ::= F_TO_DAYS",
 /*  70 */ "func_name ::= F_FROM_DAYS",
 /*  71 */ "func_name ::= F_YEAR",
 /*  72 */ "func_name ::= F_MONTH",
 /*  73 */ "func_name ::= F_DAY",
 /*  74 */ "func_name ::= F_DATE_ADD",
 /*  75 */ "func_name ::= F_DATE_SUB",
 /*  76 */ "func_name ::= F_ROUND",
 /*  77 */ "func_name ::= F_FLOOR",
    );

    /**
     * This function returns the symbolic name associated with a token
     * value.
     * @param int
     * @return string
     */
    function tokenName($tokenType)
    {
        if ($tokenType === 0) {
            return 'End of Input';
        }
        if ($tokenType > 0 && $tokenType < count(self::$yyTokenName)) {
            return self::$yyTokenName[$tokenType];
        } else {
            return "Unknown";
        }
    }

    /**
     * The following function deletes the value associated with a
     * symbol.  The symbol can be either a terminal or nonterminal.
     * @param int the symbol code
     * @param mixed the symbol's value
     */
    static function yy_destructor($yymajor, $yypminor)
    {
        switch ($yymajor) {
        /* Here is inserted the actions which take place when a
        ** terminal or non-terminal is destroyed.  This can happen
        ** when the symbol is popped from the stack during a
        ** reduce or during error processing or when a parser is 
        ** being destroyed before it is finished parsing.
        **
        ** Note: during a reduce, the only symbols destroyed are those
        ** which appear on the RHS of the rule, but which are not used
        ** inside the C code.
        */
            default:  break;   /* If no destructor action specified: do nothing */
        }
    }

    /**
     * Pop the parser's stack once.
     *
     * If there is a destructor routine associated with the token which
     * is popped from the stack, then call it.
     *
     * Return the major token number for the symbol popped.
     * @param OQLParser_yyParser
     * @return int
     */
    function yy_pop_parser_stack()
    {
        if (!count($this->yystack)) {
            return;
        }
        $yytos = array_pop($this->yystack);
        if (self::$yyTraceFILE && $this->yyidx >= 0) {
            fwrite(self::$yyTraceFILE,
                self::$yyTracePrompt . 'Popping ' . self::$yyTokenName[$yytos->major] .
                    "\n");
        }
        $yymajor = $yytos->major;
        self::yy_destructor($yymajor, $yytos->minor);
        $this->yyidx--;
        return $yymajor;
    }

    /**
     * Deallocate and destroy a parser.  Destructors are all called for
     * all stack elements before shutting the parser down.
     */
    function __destruct()
    {
        while ($this->yyidx >= 0) {
            $this->yy_pop_parser_stack();
        }
        if (is_resource(self::$yyTraceFILE)) {
            fclose(self::$yyTraceFILE);
        }
    }

    /**
     * Based on the current state and parser stack, get a list of all
     * possible lookahead tokens
     * @param int
     * @return array
     */
    function yy_get_expected_tokens($token)
    {
        $state = $this->yystack[$this->yyidx]->stateno;
        $expected = self::$yyExpectedTokens[$state];
        if (in_array($token, self::$yyExpectedTokens[$state], true)) {
            return $expected;
        }
        $stack = $this->yystack;
        $yyidx = $this->yyidx;
        do {
            $yyact = $this->yy_find_shift_action($token);
            if ($yyact >= self::YYNSTATE && $yyact < self::YYNSTATE + self::YYNRULE) {
                // reduce action
                $done = 0;
                do {
                    if ($done++ == 100) {
                        $this->yyidx = $yyidx;
                        $this->yystack = $stack;
                        // too much recursion prevents proper detection
                        // so give up
                        return array_unique($expected);
                    }
                    $yyruleno = $yyact - self::YYNSTATE;
                    $this->yyidx -= self::$yyRuleInfo[$yyruleno]['rhs'];
                    $nextstate = $this->yy_find_reduce_action(
                        $this->yystack[$this->yyidx]->stateno,
                        self::$yyRuleInfo[$yyruleno]['lhs']);
                    if (isset(self::$yyExpectedTokens[$nextstate])) {
                        $expected += self::$yyExpectedTokens[$nextstate];
                            if (in_array($token,
                                  self::$yyExpectedTokens[$nextstate], true)) {
                            $this->yyidx = $yyidx;
                            $this->yystack = $stack;
                            return array_unique($expected);
                        }
                    }
                    if ($nextstate < self::YYNSTATE) {
                        // we need to shift a non-terminal
                        $this->yyidx++;
                        $x = new OQLParser_yyStackEntry;
                        $x->stateno = $nextstate;
                        $x->major = self::$yyRuleInfo[$yyruleno]['lhs'];
                        $this->yystack[$this->yyidx] = $x;
                        continue 2;
                    } elseif ($nextstate == self::YYNSTATE + self::YYNRULE + 1) {
                        $this->yyidx = $yyidx;
                        $this->yystack = $stack;
                        // the last token was just ignored, we can't accept
                        // by ignoring input, this is in essence ignoring a
                        // syntax error!
                        return array_unique($expected);
                    } elseif ($nextstate === self::YY_NO_ACTION) {
                        $this->yyidx = $yyidx;
                        $this->yystack = $stack;
                        // input accepted, but not shifted (I guess)
                        return $expected;
                    } else {
                        $yyact = $nextstate;
                    }
                } while (true);
            }
            break;
        } while (true);
        return array_unique($expected);
    }

    /**
     * Based on the parser state and current parser stack, determine whether
     * the lookahead token is possible.
     * 
     * The parser will convert the token value to an error token if not.  This
     * catches some unusual edge cases where the parser would fail.
     * @param int
     * @return bool
     */
    function yy_is_expected_token($token)
    {
        if ($token === 0) {
            return true; // 0 is not part of this
        }
        $state = $this->yystack[$this->yyidx]->stateno;
        if (in_array($token, self::$yyExpectedTokens[$state], true)) {
            return true;
        }
        $stack = $this->yystack;
        $yyidx = $this->yyidx;
        do {
            $yyact = $this->yy_find_shift_action($token);
            if ($yyact >= self::YYNSTATE && $yyact < self::YYNSTATE + self::YYNRULE) {
                // reduce action
                $done = 0;
                do {
                    if ($done++ == 100) {
                        $this->yyidx = $yyidx;
                        $this->yystack = $stack;
                        // too much recursion prevents proper detection
                        // so give up
                        return true;
                    }
                    $yyruleno = $yyact - self::YYNSTATE;
                    $this->yyidx -= self::$yyRuleInfo[$yyruleno]['rhs'];
                    $nextstate = $this->yy_find_reduce_action(
                        $this->yystack[$this->yyidx]->stateno,
                        self::$yyRuleInfo[$yyruleno]['lhs']);
                    if (isset(self::$yyExpectedTokens[$nextstate]) &&
                          in_array($token, self::$yyExpectedTokens[$nextstate], true)) {
                        $this->yyidx = $yyidx;
                        $this->yystack = $stack;
                        return true;
                    }
                    if ($nextstate < self::YYNSTATE) {
                        // we need to shift a non-terminal
                        $this->yyidx++;
                        $x = new OQLParser_yyStackEntry;
                        $x->stateno = $nextstate;
                        $x->major = self::$yyRuleInfo[$yyruleno]['lhs'];
                        $this->yystack[$this->yyidx] = $x;
                        continue 2;
                    } elseif ($nextstate == self::YYNSTATE + self::YYNRULE + 1) {
                        $this->yyidx = $yyidx;
                        $this->yystack = $stack;
                        if (!$token) {
                            // end of input: this is valid
                            return true;
                        }
                        // the last token was just ignored, we can't accept
                        // by ignoring input, this is in essence ignoring a
                        // syntax error!
                        return false;
                    } elseif ($nextstate === self::YY_NO_ACTION) {
                        $this->yyidx = $yyidx;
                        $this->yystack = $stack;
                        // input accepted, but not shifted (I guess)
                        return true;
                    } else {
                        $yyact = $nextstate;
                    }
                } while (true);
            }
            break;
        } while (true);
        $this->yyidx = $yyidx;
        $this->yystack = $stack;
        return true;
    }

    /**
     * Find the appropriate action for a parser given the terminal
     * look-ahead token iLookAhead.
     *
     * If the look-ahead token is YYNOCODE, then check to see if the action is
     * independent of the look-ahead.  If it is, return the action, otherwise
     * return YY_NO_ACTION.
     * @param int The look-ahead token
     */
    function yy_find_shift_action($iLookAhead)
    {
        $stateno = $this->yystack[$this->yyidx]->stateno;
     
        /* if ($this->yyidx < 0) return self::YY_NO_ACTION;  */
        if (!isset(self::$yy_shift_ofst[$stateno])) {
            // no shift actions
            return self::$yy_default[$stateno];
        }
        $i = self::$yy_shift_ofst[$stateno];
        if ($i === self::YY_SHIFT_USE_DFLT) {
            return self::$yy_default[$stateno];
        }
        if ($iLookAhead == self::YYNOCODE) {
            return self::YY_NO_ACTION;
        }
        $i += $iLookAhead;
        if ($i < 0 || $i >= self::YY_SZ_ACTTAB ||
              self::$yy_lookahead[$i] != $iLookAhead) {
            if (count(self::$yyFallback) && $iLookAhead < count(self::$yyFallback)
                   && ($iFallback = self::$yyFallback[$iLookAhead]) != 0) {
                if (self::$yyTraceFILE) {
                    fwrite(self::$yyTraceFILE, self::$yyTracePrompt . "FALLBACK " .
                        self::$yyTokenName[$iLookAhead] . " => " .
                        self::$yyTokenName[$iFallback] . "\n");
                }
                return $this->yy_find_shift_action($iFallback);
            }
            return self::$yy_default[$stateno];
        } else {
            return self::$yy_action[$i];
        }
    }

    /**
     * Find the appropriate action for a parser given the non-terminal
     * look-ahead token $iLookAhead.
     *
     * If the look-ahead token is self::YYNOCODE, then check to see if the action is
     * independent of the look-ahead.  If it is, return the action, otherwise
     * return self::YY_NO_ACTION.
     * @param int Current state number
     * @param int The look-ahead token
     */
    function yy_find_reduce_action($stateno, $iLookAhead)
    {
        /* $stateno = $this->yystack[$this->yyidx]->stateno; */

        if (!isset(self::$yy_reduce_ofst[$stateno])) {
            return self::$yy_default[$stateno];
        }
        $i = self::$yy_reduce_ofst[$stateno];
        if ($i == self::YY_REDUCE_USE_DFLT) {
            return self::$yy_default[$stateno];
        }
        if ($iLookAhead == self::YYNOCODE) {
            return self::YY_NO_ACTION;
        }
        $i += $iLookAhead;
        if ($i < 0 || $i >= self::YY_SZ_ACTTAB ||
              self::$yy_lookahead[$i] != $iLookAhead) {
            return self::$yy_default[$stateno];
        } else {
            return self::$yy_action[$i];
        }
    }

    /**
     * Perform a shift action.
     * @param int The new state to shift in
     * @param int The major token to shift in
     * @param mixed the minor token to shift in
     */
    function yy_shift($yyNewState, $yyMajor, $yypMinor)
    {
        $this->yyidx++;
        if ($this->yyidx >= self::YYSTACKDEPTH) {
            $this->yyidx--;
            if (self::$yyTraceFILE) {
                fprintf(self::$yyTraceFILE, "%sStack Overflow!\n", self::$yyTracePrompt);
            }
            while ($this->yyidx >= 0) {
                $this->yy_pop_parser_stack();
            }
            /* Here code is inserted which will execute if the parser
            ** stack ever overflows */
            return;
        }
        $yytos = new OQLParser_yyStackEntry;
        $yytos->stateno = $yyNewState;
        $yytos->major = $yyMajor;
        $yytos->minor = $yypMinor;
        array_push($this->yystack, $yytos);
        if (self::$yyTraceFILE && $this->yyidx > 0) {
            fprintf(self::$yyTraceFILE, "%sShift %d\n", self::$yyTracePrompt,
                $yyNewState);
            fprintf(self::$yyTraceFILE, "%sStack:", self::$yyTracePrompt);
            for($i = 1; $i <= $this->yyidx; $i++) {
                fprintf(self::$yyTraceFILE, " %s",
                    self::$yyTokenName[$this->yystack[$i]->major]);
            }
            fwrite(self::$yyTraceFILE,"\n");
        }
    }

    /**
     * The following table contains information about every rule that
     * is used during the reduce.
     *
     * <pre>
     * array(
     *  array(
     *   int $lhs;         Symbol on the left-hand side of the rule
     *   int $nrhs;     Number of right-hand side symbols in the rule
     *  ),...
     * );
     * </pre>
     */
    static public $yyRuleInfo = array(
  array( 'lhs' => 51, 'rhs' => 1 ),
  array( 'lhs' => 51, 'rhs' => 1 ),
  array( 'lhs' => 52, 'rhs' => 4 ),
  array( 'lhs' => 52, 'rhs' => 6 ),
  array( 'lhs' => 56, 'rhs' => 2 ),
  array( 'lhs' => 56, 'rhs' => 0 ),
  array( 'lhs' => 55, 'rhs' => 2 ),
  array( 'lhs' => 55, 'rhs' => 1 ),
  array( 'lhs' => 55, 'rhs' => 0 ),
  array( 'lhs' => 57, 'rhs' => 6 ),
  array( 'lhs' => 57, 'rhs' => 4 ),
  array( 'lhs' => 58, 'rhs' => 3 ),
  array( 'lhs' => 53, 'rhs' => 1 ),
  array( 'lhs' => 60, 'rhs' => 3 ),
  array( 'lhs' => 60, 'rhs' => 3 ),
  array( 'lhs' => 60, 'rhs' => 1 ),
  array( 'lhs' => 60, 'rhs' => 1 ),
  array( 'lhs' => 60, 'rhs' => 3 ),
  array( 'lhs' => 60, 'rhs' => 4 ),
  array( 'lhs' => 64, 'rhs' => 3 ),
  array( 'lhs' => 67, 'rhs' => 1 ),
  array( 'lhs' => 67, 'rhs' => 3 ),
  array( 'lhs' => 66, 'rhs' => 0 ),
  array( 'lhs' => 66, 'rhs' => 1 ),
  array( 'lhs' => 66, 'rhs' => 3 ),
  array( 'lhs' => 68, 'rhs' => 1 ),
  array( 'lhs' => 68, 'rhs' => 3 ),
  array( 'lhs' => 69, 'rhs' => 1 ),
  array( 'lhs' => 69, 'rhs' => 1 ),
  array( 'lhs' => 69, 'rhs' => 1 ),
  array( 'lhs' => 62, 'rhs' => 1 ),
  array( 'lhs' => 62, 'rhs' => 1 ),
  array( 'lhs' => 70, 'rhs' => 1 ),
  array( 'lhs' => 71, 'rhs' => 1 ),
  array( 'lhs' => 59, 'rhs' => 3 ),
  array( 'lhs' => 54, 'rhs' => 1 ),
  array( 'lhs' => 74, 'rhs' => 1 ),
  array( 'lhs' => 72, 'rhs' => 1 ),
  array( 'lhs' => 73, 'rhs' => 1 ),
  array( 'lhs' => 61, 'rhs' => 1 ),
  array( 'lhs' => 61, 'rhs' => 1 ),
  array( 'lhs' => 61, 'rhs' => 1 ),
  array( 'lhs' => 61, 'rhs' => 1 ),
  array( 'lhs' => 61, 'rhs' => 1 ),
  array( 'lhs' => 75, 'rhs' => 1 ),
  array( 'lhs' => 75, 'rhs' => 1 ),
  array( 'lhs' => 76, 'rhs' => 1 ),
  array( 'lhs' => 76, 'rhs' => 1 ),
  array( 'lhs' => 76, 'rhs' => 1 ),
  array( 'lhs' => 76, 'rhs' => 1 ),
  array( 'lhs' => 76, 'rhs' => 1 ),
  array( 'lhs' => 76, 'rhs' => 1 ),
  array( 'lhs' => 76, 'rhs' => 1 ),
  array( 'lhs' => 76, 'rhs' => 1 ),
  array( 'lhs' => 77, 'rhs' => 1 ),
  array( 'lhs' => 77, 'rhs' => 1 ),
  array( 'lhs' => 63, 'rhs' => 1 ),
  array( 'lhs' => 63, 'rhs' => 1 ),
  array( 'lhs' => 65, 'rhs' => 1 ),
  array( 'lhs' => 65, 'rhs' => 1 ),
  array( 'lhs' => 65, 'rhs' => 1 ),
  array( 'lhs' => 65, 'rhs' => 1 ),
  array( 'lhs' => 65, 'rhs' => 1 ),
  array( 'lhs' => 65, 'rhs' => 1 ),
  array( 'lhs' => 65, 'rhs' => 1 ),
  array( 'lhs' => 65, 'rhs' => 1 ),
  array( 'lhs' => 65, 'rhs' => 1 ),
  array( 'lhs' => 65, 'rhs' => 1 ),
  array( 'lhs' => 65, 'rhs' => 1 ),
  array( 'lhs' => 65, 'rhs' => 1 ),
  array( 'lhs' => 65, 'rhs' => 1 ),
  array( 'lhs' => 65, 'rhs' => 1 ),
  array( 'lhs' => 65, 'rhs' => 1 ),
  array( 'lhs' => 65, 'rhs' => 1 ),
  array( 'lhs' => 65, 'rhs' => 1 ),
  array( 'lhs' => 65, 'rhs' => 1 ),
  array( 'lhs' => 65, 'rhs' => 1 ),
  array( 'lhs' => 65, 'rhs' => 1 ),
    );

    /**
     * The following table contains a mapping of reduce action to method name
     * that handles the reduction.
     * 
     * If a rule is not set, it has no handler.
     */
    static public $yyReduceMap = array(
        0 => 0,
        1 => 0,
        2 => 2,
        3 => 3,
        4 => 4,
        5 => 5,
        8 => 5,
        6 => 6,
        7 => 7,
        9 => 9,
        10 => 10,
        11 => 11,
        12 => 12,
        16 => 12,
        25 => 12,
        27 => 12,
        28 => 12,
        29 => 12,
        30 => 12,
        31 => 12,
        13 => 13,
        14 => 14,
        17 => 14,
        15 => 15,
        58 => 15,
        59 => 15,
        60 => 15,
        61 => 15,
        62 => 15,
        63 => 15,
        64 => 15,
        65 => 15,
        66 => 15,
        67 => 15,
        68 => 15,
        69 => 15,
        70 => 15,
        71 => 15,
        72 => 15,
        73 => 15,
        74 => 15,
        75 => 15,
        76 => 15,
        77 => 15,
        18 => 18,
        19 => 19,
        20 => 20,
        23 => 20,
        21 => 21,
        24 => 21,
        22 => 22,
        26 => 26,
        32 => 32,
        33 => 32,
        34 => 34,
        35 => 35,
        37 => 35,
        39 => 35,
        40 => 35,
        41 => 35,
        42 => 35,
        43 => 35,
        44 => 35,
        45 => 35,
        46 => 35,
        47 => 35,
        48 => 35,
        49 => 35,
        50 => 35,
        51 => 35,
        52 => 35,
        53 => 35,
        54 => 35,
        55 => 35,
        56 => 35,
        57 => 35,
        36 => 36,
        38 => 38,
    );
    /* Beginning here are the reduction cases.  A typical example
    ** follows:
    **  #line <lineno> <grammarfile>
    **   function yy_r0($yymsp){ ... }           // User supplied code
    **  #line <lineno> <thisfile>
    */
#line 7 "oql-parser.y"
    function yy_r0(){ $this->my_result = $this->yystack[$this->yyidx + 0]->minor;     }
#line 1180 "oql-parser.php"
#line 10 "oql-parser.y"
    function yy_r2(){
	$this->_retvalue = new OqlQuery($this->yystack[$this->yyidx + -2]->minor, $this->yystack[$this->yyidx + -2]->minor, $this->yystack[$this->yyidx + 0]->minor, $this->yystack[$this->yyidx + -1]->minor);
    }
#line 1185 "oql-parser.php"
#line 13 "oql-parser.y"
    function yy_r3(){
	$this->_retvalue = new OqlQuery($this->yystack[$this->yyidx + -4]->minor, $this->yystack[$this->yyidx + -2]->minor, $this->yystack[$this->yyidx + 0]->minor, $this->yystack[$this->yyidx + -1]->minor);
    }
#line 1190 "oql-parser.php"
#line 17 "oql-parser.y"
    function yy_r4(){ $this->_retvalue = $this->yystack[$this->yyidx + 0]->minor;    }
#line 1193 "oql-parser.php"
#line 18 "oql-parser.y"
    function yy_r5(){ $this->_retvalue = null;    }
#line 1196 "oql-parser.php"
#line 20 "oql-parser.y"
    function yy_r6(){
	// insert the join statement on top of the existing list
	array_unshift($this->yystack[$this->yyidx + 0]->minor, $this->yystack[$this->yyidx + -1]->minor);
	// and return the updated array
	$this->_retvalue = $this->yystack[$this->yyidx + 0]->minor;
    }
#line 1204 "oql-parser.php"
#line 26 "oql-parser.y"
    function yy_r7(){
	$this->_retvalue = Array($this->yystack[$this->yyidx + 0]->minor);
    }
#line 1209 "oql-parser.php"
#line 32 "oql-parser.y"
    function yy_r9(){
	// create an array with one single item
	$this->_retvalue = new OqlJoinSpec($this->yystack[$this->yyidx + -4]->minor, $this->yystack[$this->yyidx + -2]->minor, $this->yystack[$this->yyidx + 0]->minor);
    }
#line 1215 "oql-parser.php"
#line 37 "oql-parser.y"
    function yy_r10(){
	// create an array with one single item
	$this->_retvalue = new OqlJoinSpec($this->yystack[$this->yyidx + -2]->minor, $this->yystack[$this->yyidx + -2]->minor, $this->yystack[$this->yyidx + 0]->minor);
    }
#line 1221 "oql-parser.php"
#line 42 "oql-parser.y"
    function yy_r11(){ $this->_retvalue = new BinaryOqlExpression($this->yystack[$this->yyidx + -2]->minor, '=', $this->yystack[$this->yyidx + 0]->minor);     }
#line 1224 "oql-parser.php"
#line 44 "oql-parser.y"
    function yy_r12(){ $this->_retvalue = $this->yystack[$this->yyidx + 0]->minor;     }
#line 1227 "oql-parser.php"
#line 46 "oql-parser.y"
    function yy_r13(){ $this->_retvalue = $this->yystack[$this->yyidx + -1]->minor;     }
#line 1230 "oql-parser.php"
#line 47 "oql-parser.y"
    function yy_r14(){ $this->_retvalue = new BinaryOqlExpression($this->yystack[$this->yyidx + -2]->minor, $this->yystack[$this->yyidx + -1]->minor, $this->yystack[$this->yyidx + 0]->minor);     }
#line 1233 "oql-parser.php"
#line 48 "oql-parser.y"
    function yy_r15(){ $this->_retvalue=$this->yystack[$this->yyidx + 0]->minor;     }
#line 1236 "oql-parser.php"
#line 51 "oql-parser.y"
    function yy_r18(){ $this->_retvalue = new FunctionOqlExpression($this->yystack[$this->yyidx + -3]->minor, $this->yystack[$this->yyidx + -1]->minor);     }
#line 1239 "oql-parser.php"
#line 54 "oql-parser.y"
    function yy_r19(){
	$this->_retvalue = new ListOqlExpression($this->yystack[$this->yyidx + -1]->minor);
    }
#line 1244 "oql-parser.php"
#line 57 "oql-parser.y"
    function yy_r20(){
	$this->_retvalue = array($this->yystack[$this->yyidx + 0]->minor);
    }
#line 1249 "oql-parser.php"
#line 60 "oql-parser.y"
    function yy_r21(){
	array_push($this->yystack[$this->yyidx + -2]->minor, $this->yystack[$this->yyidx + 0]->minor);
	$this->_retvalue = $this->yystack[$this->yyidx + -2]->minor;
    }
#line 1255 "oql-parser.php"
#line 65 "oql-parser.y"
    function yy_r22(){
	$this->_retvalue = array();
    }
#line 1260 "oql-parser.php"
#line 76 "oql-parser.y"
    function yy_r26(){ $this->_retvalue = new IntervalOqlExpression($this->yystack[$this->yyidx + -1]->minor, $this->yystack[$this->yyidx + 0]->minor);     }
#line 1263 "oql-parser.php"
#line 85 "oql-parser.y"
    function yy_r32(){ $this->_retvalue = new ScalarOqlExpression($this->yystack[$this->yyidx + 0]->minor);     }
#line 1266 "oql-parser.php"
#line 88 "oql-parser.y"
    function yy_r34(){ $this->_retvalue = new FieldOqlExpression($this->m_iCol, $this->yystack[$this->yyidx + 0]->minor, $this->yystack[$this->yyidx + -2]->minor);     }
#line 1269 "oql-parser.php"
#line 89 "oql-parser.y"
    function yy_r35(){$this->_retvalue=$this->yystack[$this->yyidx + 0]->minor;    }
#line 1272 "oql-parser.php"
#line 91 "oql-parser.y"
    function yy_r36(){
	if ($this->yystack[$this->yyidx + 0]->minor[0] == '`')
	{
		$this->_retvalue = substr($this->yystack[$this->yyidx + 0]->minor, 1, strlen($this->yystack[$this->yyidx + 0]->minor) - 2);
	}
	else
	{
		$this->_retvalue = $this->yystack[$this->yyidx + 0]->minor;
	}
    }
#line 1284 "oql-parser.php"
#line 103 "oql-parser.y"
    function yy_r38(){$this->_retvalue=stripslashes(substr($this->yystack[$this->yyidx + 0]->minor, 1, strlen($this->yystack[$this->yyidx + 0]->minor) - 2));    }
#line 1287 "oql-parser.php"

    /**
     * placeholder for the left hand side in a reduce operation.
     * 
     * For a parser with a rule like this:
     * <pre>
     * rule(A) ::= B. { A = 1; }
     * </pre>
     * 
     * The parser will translate to something like:
     * 
     * <code>
     * function yy_r0(){$this->_retvalue = 1;}
     * </code>
     */
    private $_retvalue;

    /**
     * Perform a reduce action and the shift that must immediately
     * follow the reduce.
     * 
     * For a rule such as:
     * 
     * <pre>
     * A ::= B blah C. { dosomething(); }
     * </pre>
     * 
     * This function will first call the action, if any, ("dosomething();" in our
     * example), and then it will pop three states from the stack,
     * one for each entry on the right-hand side of the expression
     * (B, blah, and C in our example rule), and then push the result of the action
     * back on to the stack with the resulting state reduced to (as described in the .out
     * file)
     * @param int Number of the rule by which to reduce
     */
    function yy_reduce($yyruleno)
    {
        //int $yygoto;                     /* The next state */
        //int $yyact;                      /* The next action */
        //mixed $yygotominor;        /* The LHS of the rule reduced */
        //OQLParser_yyStackEntry $yymsp;            /* The top of the parser's stack */
        //int $yysize;                     /* Amount to pop the stack */
        $yymsp = $this->yystack[$this->yyidx];
        if (self::$yyTraceFILE && $yyruleno >= 0 
              && $yyruleno < count(self::$yyRuleName)) {
            fprintf(self::$yyTraceFILE, "%sReduce (%d) [%s].\n",
                self::$yyTracePrompt, $yyruleno,
                self::$yyRuleName[$yyruleno]);
        }

        $this->_retvalue = $yy_lefthand_side = null;
        if (array_key_exists($yyruleno, self::$yyReduceMap)) {
            // call the action
            $this->_retvalue = null;
            $this->{'yy_r' . self::$yyReduceMap[$yyruleno]}();
            $yy_lefthand_side = $this->_retvalue;
        }
        $yygoto = self::$yyRuleInfo[$yyruleno]['lhs'];
        $yysize = self::$yyRuleInfo[$yyruleno]['rhs'];
        $this->yyidx -= $yysize;
        for($i = $yysize; $i; $i--) {
            // pop all of the right-hand side parameters
            array_pop($this->yystack);
        }
        $yyact = $this->yy_find_reduce_action($this->yystack[$this->yyidx]->stateno, $yygoto);
        if ($yyact < self::YYNSTATE) {
            /* If we are not debugging and the reduce action popped at least
            ** one element off the stack, then we can push the new element back
            ** onto the stack here, and skip the stack overflow test in yy_shift().
            ** That gives a significant speed improvement. */
            if (!self::$yyTraceFILE && $yysize) {
                $this->yyidx++;
                $x = new OQLParser_yyStackEntry;
                $x->stateno = $yyact;
                $x->major = $yygoto;
                $x->minor = $yy_lefthand_side;
                $this->yystack[$this->yyidx] = $x;
            } else {
                $this->yy_shift($yyact, $yygoto, $yy_lefthand_side);
            }
        } elseif ($yyact == self::YYNSTATE + self::YYNRULE + 1) {
            $this->yy_accept();
        }
    }

    /**
     * The following code executes when the parse fails
     * 
     * Code from %parse_fail is inserted here
     */
    function yy_parse_failed()
    {
        if (self::$yyTraceFILE) {
            fprintf(self::$yyTraceFILE, "%sFail!\n", self::$yyTracePrompt);
        }
        while ($this->yyidx >= 0) {
            $this->yy_pop_parser_stack();
        }
        /* Here code is inserted which will be executed whenever the
        ** parser fails */
    }

    /**
     * The following code executes when a syntax error first occurs.
     * 
     * %syntax_error code is inserted here
     * @param int The major type of the error token
     * @param mixed The minor type of the error token
     */
    function yy_syntax_error($yymajor, $TOKEN)
    {
#line 3 "oql-parser.y"
 
throw new OQLParserException($this->m_sSourceQuery, $this->m_iLine, $this->m_iCol, $this->tokenName($yymajor), $TOKEN);
#line 1403 "oql-parser.php"
    }

    /**
     * The following is executed when the parser accepts
     * 
     * %parse_accept code is inserted here
     */
    function yy_accept()
    {
        if (self::$yyTraceFILE) {
            fprintf(self::$yyTraceFILE, "%sAccept!\n", self::$yyTracePrompt);
        }
        while ($this->yyidx >= 0) {
            $stack = $this->yy_pop_parser_stack();
        }
        /* Here code is inserted which will be executed whenever the
        ** parser accepts */
    }

    /**
     * The main parser program.
     * 
     * The first argument is the major token number.  The second is
     * the token value string as scanned from the input.
     *
     * @param int the token number
     * @param mixed the token value
     * @param mixed any extra arguments that should be passed to handlers
     */
    function doParse($yymajor, $yytokenvalue)
    {
//        $yyact;            /* The parser action. */
//        $yyendofinput;     /* True if we are at the end of input */
        $yyerrorhit = 0;   /* True if yymajor has invoked an error */
        
        /* (re)initialize the parser, if necessary */
        if ($this->yyidx === null || $this->yyidx < 0) {
            /* if ($yymajor == 0) return; // not sure why this was here... */
            $this->yyidx = 0;
            $this->yyerrcnt = -1;
            $x = new OQLParser_yyStackEntry;
            $x->stateno = 0;
            $x->major = 0;
            $this->yystack = array();
            array_push($this->yystack, $x);
        }
        $yyendofinput = ($yymajor==0);
        
        if (self::$yyTraceFILE) {
            fprintf(self::$yyTraceFILE, "%sInput %s\n",
                self::$yyTracePrompt, self::$yyTokenName[$yymajor]);
        }
        
        do {
            $yyact = $this->yy_find_shift_action($yymajor);
            if ($yymajor < self::YYERRORSYMBOL &&
                  !$this->yy_is_expected_token($yymajor)) {
                // force a syntax error
                $yyact = self::YY_ERROR_ACTION;
            }
            if ($yyact < self::YYNSTATE) {
                $this->yy_shift($yyact, $yymajor, $yytokenvalue);
                $this->yyerrcnt--;
                if ($yyendofinput && $this->yyidx >= 0) {
                    $yymajor = 0;
                } else {
                    $yymajor = self::YYNOCODE;
                }
            } elseif ($yyact < self::YYNSTATE + self::YYNRULE) {
                $this->yy_reduce($yyact - self::YYNSTATE);
            } elseif ($yyact == self::YY_ERROR_ACTION) {
                if (self::$yyTraceFILE) {
                    fprintf(self::$yyTraceFILE, "%sSyntax Error!\n",
                        self::$yyTracePrompt);
                }
                if (self::YYERRORSYMBOL) {
                    /* A syntax error has occurred.
                    ** The response to an error depends upon whether or not the
                    ** grammar defines an error token "ERROR".  
                    **
                    ** This is what we do if the grammar does define ERROR:
                    **
                    **  * Call the %syntax_error function.
                    **
                    **  * Begin popping the stack until we enter a state where
                    **    it is legal to shift the error symbol, then shift
                    **    the error symbol.
                    **
                    **  * Set the error count to three.
                    **
                    **  * Begin accepting and shifting new tokens.  No new error
                    **    processing will occur until three tokens have been
                    **    shifted successfully.
                    **
                    */
                    if ($this->yyerrcnt < 0) {
                        $this->yy_syntax_error($yymajor, $yytokenvalue);
                    }
                    $yymx = $this->yystack[$this->yyidx]->major;
                    if ($yymx == self::YYERRORSYMBOL || $yyerrorhit ){
                        if (self::$yyTraceFILE) {
                            fprintf(self::$yyTraceFILE, "%sDiscard input token %s\n",
                                self::$yyTracePrompt, self::$yyTokenName[$yymajor]);
                        }
                        $this->yy_destructor($yymajor, $yytokenvalue);
                        $yymajor = self::YYNOCODE;
                    } else {
                        while ($this->yyidx >= 0 &&
                                 $yymx != self::YYERRORSYMBOL &&
        ($yyact = $this->yy_find_shift_action(self::YYERRORSYMBOL)) >= self::YYNSTATE
                              ){
                            $this->yy_pop_parser_stack();
                        }
                        if ($this->yyidx < 0 || $yymajor==0) {
                            $this->yy_destructor($yymajor, $yytokenvalue);
                            $this->yy_parse_failed();
                            $yymajor = self::YYNOCODE;
                        } elseif ($yymx != self::YYERRORSYMBOL) {
                            $u2 = 0;
                            $this->yy_shift($yyact, self::YYERRORSYMBOL, $u2);
                        }
                    }
                    $this->yyerrcnt = 3;
                    $yyerrorhit = 1;
                } else {
                    /* YYERRORSYMBOL is not defined */
                    /* This is what we do if the grammar does not define ERROR:
                    **
                    **  * Report an error message, and throw away the input token.
                    **
                    **  * If the input token is $, then fail the parse.
                    **
                    ** As before, subsequent error messages are suppressed until
                    ** three input tokens have been successfully shifted.
                    */
                    if ($this->yyerrcnt <= 0) {
                        $this->yy_syntax_error($yymajor, $yytokenvalue);
                    }
                    $this->yyerrcnt = 3;
                    $this->yy_destructor($yymajor, $yytokenvalue);
                    if ($yyendofinput) {
                        $this->yy_parse_failed();
                    }
                    $yymajor = self::YYNOCODE;
                }
            } else {
                $this->yy_accept();
                $yymajor = self::YYNOCODE;
            }            
        } while ($yymajor != self::YYNOCODE && $this->yyidx >= 0);
    }
}#line 151 "oql-parser.y"



class OQLParserException extends OQLException
{
	public function __construct($sInput, $iLine, $iCol, $sTokenName, $sTokenValue)
	{
		$sIssue = "Unexpected token $sTokenName";
	
		parent::__construct($sIssue, $sInput, $iLine, $iCol, $sTokenValue);
	}
}

class OQLParser extends OQLParserRaw
{
	// dirty, but working for us (no other mean to get the final result :-(
   protected $my_result;

	public function GetResult()
	{
		return $this->my_result;
	}

   // More info on the source query and the current position while parsing it
   // Data used when an exception is raised
	protected $m_iLine; // still not used
	protected $m_iCol;
	protected $m_sSourceQuery;

	public function __construct($sQuery)
	{
		$this->m_iLine = 0;
		$this->m_iCol = 0;
		$this->m_sSourceQuery = $sQuery;
		// no constructor - parent::__construct();
	}
	
	public function doParse($token, $value, $iCurrPosition = 0)
	{
		$this->m_iCol = $iCurrPosition;

		return parent::DoParse($token, $value);
	}

	public function doFinish()
	{
		$this->doParse(0, 0);
		return $this->my_result;
	}
	
	public function __destruct()
	{
		// Bug in the original destructor, causing an infinite loop !
		// This is a real issue when a fatal error occurs on the first token (the error could not be seen)
		if (is_null($this->yyidx))
		{
			$this->yyidx = -1;
		}
		parent::__destruct();
	}
}

#line 1620 "oql-parser.php"

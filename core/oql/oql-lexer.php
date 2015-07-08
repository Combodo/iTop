<?php

// Copyright (C) 2010-2015 Combodo SARL
//
//   This file is part of iTop.
//
//   iTop is free software; you can redistribute it and/or modify	
//   it under the terms of the GNU Affero General Public License as published by
//   the Free Software Foundation, either version 3 of the License, or
//   (at your option) any later version.
//
//   iTop is distributed in the hope that it will be useful,
//   but WITHOUT ANY WARRANTY; without even the implied warranty of
//   MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
//   GNU Affero General Public License for more details.
//
//   You should have received a copy of the GNU Affero General Public License
//   along with iTop. If not, see <http://www.gnu.org/licenses/>


/**
 * OQL syntax analyzer, to be used prior to run the lexical analyzer
 *
 * @copyright   Copyright (C) 2010-2015 Combodo SARL
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

// Notes (from the source file: oql-lexer.plex) - Romain
//
// The strval rule is a little bit cryptic.
// This is due to both a bug in the lexer generator and the complexity of our need
// The rule means: either a quoted string with ", or a quoted string with '
//                 literal " (resp. ') must be escaped by a \
//                 \ must be escaped by an additional \
// 
// Here are the issues and limitation found in the lexer generator:
// * Matching simple quotes is an issue, because regexp are not correctly escaped (and the ESC code is escaped itself)
//    Workaround: insert '.chr(39).' which will be a real ' in the end
// * Matching an alternate regexp is an issue because you must specify  "|^...."
//   and the regexp parser will not accept that syntax
//    Workaround: insert '.chr(94).' which will be a real ^
//
// Let's analyze an overview of the regexp, we have
// 1) The strval rule in the lexer definition
//     /"([^\\"]|\\"|\\\\)*"|'.chr(94).chr(39).'([^\\'.chr(39).']|\\'.chr(39).'|\\\\)*'.chr(39).'/
// 2) Becomes the php expression in the lexer
//    (note the escaped double quotes, hopefully having no effect, but showing where the issue is!)
//     $myRegexp = '/^\"([^\\\\\"]|\\\\\"|\\\\\\\\)*\"|'.chr(94).chr(39).'([^\\\\'.chr(39).']|\\\\'.chr(39).'|\\\\\\\\)*'.chr(39).'/';
//
// To be fixed in LexerGenerator/Parser.y, in doLongestMatch (doFirstMatch is ok)
//
//
// Now, let's explain how the regexp has been designed.
// Here is a simplified version, dealing with simple quotes, and based on the assumption that the lexer generator has been fixed!
// The strval rule in the lexer definition
//     /'([^\\']*(\\')*(\\\\)*)*'/
// This means anything containing \\ or \' or any other char but a standalone ' or \
// This means ' or \ could not be found without a preceding \
//
class OQLLexerRaw
{
    protected $data;  // input string
    public $token;  // token id
    public $value;  // token string representation
    protected $line;  // current line
    protected $count; // current column

    function __construct($data)
    {
        $this->data  = $data;
        $this->count = 0;
        $this->line  = 1;
    }


    private $_yy_state = 1;
    private $_yy_stack = array();

    function yylex()
    {
        return $this->{'yylex' . $this->_yy_state}();
    }

    function yypushstate($state)
    {
        array_push($this->_yy_stack, $this->_yy_state);
        $this->_yy_state = $state;
    }

    function yypopstate()
    {
        $this->_yy_state = array_pop($this->_yy_stack);
    }

    function yybegin($state)
    {
        $this->_yy_state = $state;
    }




    function yylex1()
    {
        if ($this->count >= strlen($this->data)) {
            return false; // end of input
        }
        do {
            $rules = array(
                '/\G[ \t\n\r]+/ ',
                '/\GUNION/ ',
                '/\GSELECT/ ',
                '/\GFROM/ ',
                '/\GAS/ ',
                '/\GWHERE/ ',
                '/\GJOIN/ ',
                '/\GON/ ',
                '/\G\// ',
                '/\G\\*/ ',
                '/\G\\+/ ',
                '/\G-/ ',
                '/\GAND/ ',
                '/\GOR/ ',
                '/\G\\|/ ',
                '/\G&/ ',
                '/\G\\^/ ',
                '/\G<</ ',
                '/\G>>/ ',
                '/\G,/ ',
                '/\G\\(/ ',
                '/\G\\)/ ',
                '/\GREGEXP/ ',
                '/\G=/ ',
                '/\G!=/ ',
                '/\G>/ ',
                '/\G</ ',
                '/\G>=/ ',
                '/\G<=/ ',
                '/\GLIKE/ ',
                '/\GNOT LIKE/ ',
                '/\GIN/ ',
                '/\GNOT IN/ ',
                '/\GINTERVAL/ ',
                '/\GIF/ ',
                '/\GELT/ ',
                '/\GCOALESCE/ ',
                '/\GISNULL/ ',
                '/\GCONCAT/ ',
                '/\GSUBSTR/ ',
                '/\GTRIM/ ',
                '/\GDATE/ ',
                '/\GDATE_FORMAT/ ',
                '/\GCURRENT_DATE/ ',
                '/\GNOW/ ',
                '/\GTIME/ ',
                '/\GTO_DAYS/ ',
                '/\GFROM_DAYS/ ',
                '/\GYEAR/ ',
                '/\GMONTH/ ',
                '/\GDAY/ ',
                '/\GHOUR/ ',
                '/\GMINUTE/ ',
                '/\GSECOND/ ',
                '/\GDATE_ADD/ ',
                '/\GDATE_SUB/ ',
                '/\GROUND/ ',
                '/\GFLOOR/ ',
                '/\GINET_ATON/ ',
                '/\GINET_NTOA/ ',
                '/\GBELOW/ ',
                '/\GBELOW STRICT/ ',
                '/\GNOT BELOW/ ',
                '/\GNOT BELOW STRICT/ ',
                '/\GABOVE/ ',
                '/\GABOVE STRICT/ ',
                '/\GNOT ABOVE/ ',
                '/\GNOT ABOVE STRICT/ ',
                '/\G(0x[0-9a-fA-F]+)/ ',
                '/\G([0-9]+)/ ',
                '/\G\"([^\\\\\"]|\\\\\"|\\\\\\\\)*\"|'.chr(94).chr(39).'([^\\\\'.chr(39).']|\\\\'.chr(39).'|\\\\\\\\)*'.chr(39).'/ ',
                '/\G([_a-zA-Z][_a-zA-Z0-9]*|`[^`]+`)/ ',
                '/\G:([_a-zA-Z][_a-zA-Z0-9]*->[_a-zA-Z][_a-zA-Z0-9]*|[_a-zA-Z][_a-zA-Z0-9]*)/ ',
                '/\G\\./ ',
            );
            $match = false;
            foreach ($rules as $index => $rule) {
                if (preg_match($rule, substr($this->data, $this->count), $yymatches)) {
                    if ($match) {
                        if (strlen($yymatches[0]) > strlen($match[0][0])) {
                            $match = array($yymatches, $index); // matches, token
                        }
                    } else {
                        $match = array($yymatches, $index);
                    }
                }
            }
            if (!$match) {
                throw new Exception('Unexpected input at line ' . $this->line .
                    ': ' . $this->data[$this->count]);
            }
            $this->token = $match[1];
            $this->value = $match[0][0];
            $yysubmatches = $match[0];
            array_shift($yysubmatches);
            if (!$yysubmatches) {
                $yysubmatches = array();
            }
            $r = $this->{'yy_r1_' . $this->token}($yysubmatches);
            if ($r === null) {
                $this->count += strlen($this->value);
                $this->line += substr_count($this->value, "\n");
                // accept this token
                return true;
            } elseif ($r === true) {
                // we have changed state
                // process this token in the new state
                return $this->yylex();
            } elseif ($r === false) {
                $this->count += strlen($this->value);
                $this->line += substr_count($this->value, "\n");
                if ($this->count >= strlen($this->data)) {
                    return false; // end of input
                }
                // skip this token
                continue;
            } else {
                $yy_yymore_patterns = array_slice($rules, $this->token, true);
                // yymore is needed
                do {
                    if (!isset($yy_yymore_patterns[$this->token])) {
                        throw new Exception('cannot do yymore for the last token');
                    }
                    $match = false;
                    foreach ($yy_yymore_patterns[$this->token] as $index => $rule) {
                        if (preg_match('/' . $rule . '/',
                                $this->data, $yymatches, null, $this->count)) {
                            $yymatches = array_filter($yymatches, 'strlen'); // remove empty sub-patterns
                            if ($match) {
                                if (strlen($yymatches[0]) > strlen($match[0][0])) {
                                    $match = array($yymatches, $index); // matches, token
                                }
                            } else {
                                $match = array($yymatches, $index);
                            }
                        }
                    }
                    if (!$match) {
                        throw new Exception('Unexpected input at line ' . $this->line .
                            ': ' . $this->data[$this->count]);
                    }
                    $this->token = $match[1];
                    $this->value = $match[0][0];
                    $yysubmatches = $match[0];
                    array_shift($yysubmatches);
                    if (!$yysubmatches) {
                        $yysubmatches = array();
                    }
                    $this->line = substr_count($this->value, "\n");
                    $r = $this->{'yy_r1_' . $this->token}();
                } while ($r !== null || !$r);
                if ($r === true) {
                    // we have changed state
                    // process this token in the new state
                    return $this->yylex();
                } else {
                    // accept
                    $this->count += strlen($this->value);
                    $this->line += substr_count($this->value, "\n");
                    return true;
                }
            }
        } while (true);

    } // end function

    function yy_r1_0($yy_subpatterns)
    {

	return false;
    }
    function yy_r1_1($yy_subpatterns)
    {

	$this->token = OQLParser::UNION;
    }
    function yy_r1_2($yy_subpatterns)
    {

	$this->token = OQLParser::SELECT;
    }
    function yy_r1_3($yy_subpatterns)
    {

	$this->token = OQLParser::FROM;
    }
    function yy_r1_4($yy_subpatterns)
    {

	$this->token = OQLParser::AS_ALIAS;
    }
    function yy_r1_5($yy_subpatterns)
    {

	$this->token = OQLParser::WHERE;
    }
    function yy_r1_6($yy_subpatterns)
    {

	$this->token = OQLParser::JOIN;
    }
    function yy_r1_7($yy_subpatterns)
    {

	$this->token = OQLParser::ON;
    }
    function yy_r1_8($yy_subpatterns)
    {

	$this->token = OQLParser::MATH_DIV;
    }
    function yy_r1_9($yy_subpatterns)
    {

	$this->token = OQLParser::MATH_MULT;
    }
    function yy_r1_10($yy_subpatterns)
    {

	$this->token = OQLParser::MATH_PLUS;
    }
    function yy_r1_11($yy_subpatterns)
    {

	$this->token = OQLParser::MATH_MINUS;
    }
    function yy_r1_12($yy_subpatterns)
    {

	$this->token = OQLParser::LOG_AND;
    }
    function yy_r1_13($yy_subpatterns)
    {

	$this->token = OQLParser::LOG_OR;
    }
    function yy_r1_14($yy_subpatterns)
    {

	$this->token = OQLParser::BITWISE_OR;
    }
    function yy_r1_15($yy_subpatterns)
    {

	$this->token = OQLParser::BITWISE_AND;
    }
    function yy_r1_16($yy_subpatterns)
    {

	$this->token = OQLParser::BITWISE_XOR;
    }
    function yy_r1_17($yy_subpatterns)
    {

	$this->token = OQLParser::BITWISE_LEFT_SHIFT;
    }
    function yy_r1_18($yy_subpatterns)
    {

	$this->token = OQLParser::BITWISE_RIGHT_SHIFT;
    }
    function yy_r1_19($yy_subpatterns)
    {

	$this->token = OQLParser::COMA;
    }
    function yy_r1_20($yy_subpatterns)
    {

	$this->token = OQLParser::PAR_OPEN;
    }
    function yy_r1_21($yy_subpatterns)
    {

	$this->token = OQLParser::PAR_CLOSE;
    }
    function yy_r1_22($yy_subpatterns)
    {

	$this->token = OQLParser::REGEXP;
    }
    function yy_r1_23($yy_subpatterns)
    {

	$this->token = OQLParser::EQ;
    }
    function yy_r1_24($yy_subpatterns)
    {

	$this->token = OQLParser::NOT_EQ;
    }
    function yy_r1_25($yy_subpatterns)
    {

	$this->token = OQLParser::GT;
    }
    function yy_r1_26($yy_subpatterns)
    {

	$this->token = OQLParser::LT;
    }
    function yy_r1_27($yy_subpatterns)
    {

	$this->token = OQLParser::GE;
    }
    function yy_r1_28($yy_subpatterns)
    {

	$this->token = OQLParser::LE;
    }
    function yy_r1_29($yy_subpatterns)
    {

	$this->token = OQLParser::LIKE;
    }
    function yy_r1_30($yy_subpatterns)
    {

	$this->token = OQLParser::NOT_LIKE;
    }
    function yy_r1_31($yy_subpatterns)
    {

	$this->token = OQLParser::IN;
    }
    function yy_r1_32($yy_subpatterns)
    {

	$this->token = OQLParser::NOT_IN;
    }
    function yy_r1_33($yy_subpatterns)
    {

	$this->token = OQLParser::INTERVAL;
    }
    function yy_r1_34($yy_subpatterns)
    {

	$this->token = OQLParser::F_IF;
    }
    function yy_r1_35($yy_subpatterns)
    {

	$this->token = OQLParser::F_ELT;
    }
    function yy_r1_36($yy_subpatterns)
    {

	$this->token = OQLParser::F_COALESCE;
    }
    function yy_r1_37($yy_subpatterns)
    {

	$this->token = OQLParser::F_ISNULL;
    }
    function yy_r1_38($yy_subpatterns)
    {

	$this->token = OQLParser::F_CONCAT;
    }
    function yy_r1_39($yy_subpatterns)
    {

	$this->token = OQLParser::F_SUBSTR;
    }
    function yy_r1_40($yy_subpatterns)
    {

	$this->token = OQLParser::F_TRIM;
    }
    function yy_r1_41($yy_subpatterns)
    {

	$this->token = OQLParser::F_DATE;
    }
    function yy_r1_42($yy_subpatterns)
    {

	$this->token = OQLParser::F_DATE_FORMAT;
    }
    function yy_r1_43($yy_subpatterns)
    {

	$this->token = OQLParser::F_CURRENT_DATE;
    }
    function yy_r1_44($yy_subpatterns)
    {

	$this->token = OQLParser::F_NOW;
    }
    function yy_r1_45($yy_subpatterns)
    {

	$this->token = OQLParser::F_TIME;
    }
    function yy_r1_46($yy_subpatterns)
    {

	$this->token = OQLParser::F_TO_DAYS;
    }
    function yy_r1_47($yy_subpatterns)
    {

	$this->token = OQLParser::F_FROM_DAYS;
    }
    function yy_r1_48($yy_subpatterns)
    {

	$this->token = OQLParser::F_YEAR;
    }
    function yy_r1_49($yy_subpatterns)
    {

	$this->token = OQLParser::F_MONTH;
    }
    function yy_r1_50($yy_subpatterns)
    {

	$this->token = OQLParser::F_DAY;
    }
    function yy_r1_51($yy_subpatterns)
    {

	$this->token = OQLParser::F_HOUR;
    }
    function yy_r1_52($yy_subpatterns)
    {

	$this->token = OQLParser::F_MINUTE;
    }
    function yy_r1_53($yy_subpatterns)
    {

	$this->token = OQLParser::F_SECOND;
    }
    function yy_r1_54($yy_subpatterns)
    {

	$this->token = OQLParser::F_DATE_ADD;
    }
    function yy_r1_55($yy_subpatterns)
    {

	$this->token = OQLParser::F_DATE_SUB;
    }
    function yy_r1_56($yy_subpatterns)
    {

	$this->token = OQLParser::F_ROUND;
    }
    function yy_r1_57($yy_subpatterns)
    {

	$this->token = OQLParser::F_FLOOR;
    }
    function yy_r1_58($yy_subpatterns)
    {

	$this->token = OQLParser::F_INET_ATON;
    }
    function yy_r1_59($yy_subpatterns)
    {

	$this->token = OQLParser::F_INET_NTOA;
    }
    function yy_r1_60($yy_subpatterns)
    {

	$this->token = OQLParser::BELOW;
    }
    function yy_r1_61($yy_subpatterns)
    {

	$this->token = OQLParser::BELOW_STRICT;
    }
    function yy_r1_62($yy_subpatterns)
    {

	$this->token = OQLParser::NOT_BELOW;
    }
    function yy_r1_63($yy_subpatterns)
    {

	$this->token = OQLParser::NOT_BELOW_STRICT;
    }
    function yy_r1_64($yy_subpatterns)
    {

	$this->token = OQLParser::ABOVE;
    }
    function yy_r1_65($yy_subpatterns)
    {

	$this->token = OQLParser::ABOVE_STRICT;
    }
    function yy_r1_66($yy_subpatterns)
    {

	$this->token = OQLParser::NOT_ABOVE;
    }
    function yy_r1_67($yy_subpatterns)
    {

	$this->token = OQLParser::NOT_ABOVE_STRICT;
    }
    function yy_r1_68($yy_subpatterns)
    {

	$this->token = OQLParser::HEXVAL;
    }
    function yy_r1_69($yy_subpatterns)
    {

	$this->token = OQLParser::NUMVAL;
    }
    function yy_r1_70($yy_subpatterns)
    {

	$this->token = OQLParser::STRVAL;
    }
    function yy_r1_71($yy_subpatterns)
    {

	$this->token = OQLParser::NAME;
    }
    function yy_r1_72($yy_subpatterns)
    {

	$this->token = OQLParser::VARNAME;
    }
    function yy_r1_73($yy_subpatterns)
    {

	$this->token = OQLParser::DOT;
    }


}

define('UNEXPECTED_INPUT_AT_LINE', 'Unexpected input at line');

class OQLLexerException extends OQLException
{
	public function __construct($sInput, $iLine, $iCol, $sUnexpected)
	{
		parent::__construct("Syntax error", $sInput, $iLine, $iCol, $sUnexpected);
	}
}

class OQLLexer extends OQLLexerRaw 
{
	public function getTokenPos()
	{
		return max(0, $this->count - strlen($this->value));
	}

   function yylex()
   {
      try
      {
      	return parent::yylex();
		}
		catch (Exception $e)
		{
			$sMessage = $e->getMessage();
			if (substr($sMessage, 0, strlen(UNEXPECTED_INPUT_AT_LINE)) == UNEXPECTED_INPUT_AT_LINE)
			{
				$sLineAndChar = substr($sMessage, strlen(UNEXPECTED_INPUT_AT_LINE));
				if (preg_match('#^([0-9]+): (.+)$#', $sLineAndChar, $aMatches))
				{
					$iLine = $aMatches[1];
					$sUnexpected = $aMatches[2];
					throw new OQLLexerException($this->data, $iLine, $this->count, $sUnexpected);
				}
			}
			// Default: forward the exception
			throw $e;
		}
	}
}
?>

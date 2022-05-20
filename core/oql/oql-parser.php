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
        return $this->string;
    }

    function offsetExists($offset): bool
    {
        return isset($this->metadata[$offset]);
    }

	// Return type mixed is not supported by PHP 7.4, we can remove the following PHP attribute and add the return type once iTop min PHP version is PHP 8.0+
	#[\ReturnTypeWillChange]
    function offsetGet($offset)
    {
        return $this->metadata[$offset];
    }

    function offsetSet($offset, $value): void
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

    function offsetUnset($offset): void
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
#line 24 "..\oql-parser.y"
class OQLParserRaw#line 102 "..\oql-parser.php"
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
    const UNION                          =  1;
    const SELECT                         =  2;
    const AS_ALIAS                       =  3;
    const FROM                           =  4;
    const COMA                           =  5;
    const WHERE                          =  6;
    const JOIN                           =  7;
    const ON                             =  8;
    const EQ                             =  9;
    const BELOW                          = 10;
    const BELOW_STRICT                   = 11;
    const NOT_BELOW                      = 12;
    const NOT_BELOW_STRICT               = 13;
    const ABOVE                          = 14;
    const ABOVE_STRICT                   = 15;
    const NOT_ABOVE                      = 16;
    const NOT_ABOVE_STRICT               = 17;
    const PAR_OPEN                       = 18;
    const PAR_CLOSE                      = 19;
    const INTERVAL                       = 20;
    const F_SECOND                       = 21;
    const F_MINUTE                       = 22;
    const F_HOUR                         = 23;
    const F_DAY                          = 24;
    const F_MONTH                        = 25;
    const F_YEAR                         = 26;
    const NULL_VAL                       = 27;
    const DOT                            = 28;
    const VARNAME                        = 29;
    const NAME                           = 30;
    const NUMVAL                         = 31;
    const MATH_MINUS                     = 32;
    const HEXVAL                         = 33;
    const STRVAL                         = 34;
    const REGEXP                         = 35;
    const NOT_EQ                         = 36;
    const LOG_AND                        = 37;
    const LOG_OR                         = 38;
    const MATH_DIV                       = 39;
    const MATH_MULT                      = 40;
    const MATH_PLUS                      = 41;
    const GT                             = 42;
    const LT                             = 43;
    const GE                             = 44;
    const LE                             = 45;
    const LIKE                           = 46;
    const NOT_LIKE                       = 47;
    const MATCHES                        = 48;
    const BITWISE_LEFT_SHIFT             = 49;
    const BITWISE_RIGHT_SHIFT            = 50;
    const BITWISE_AND                    = 51;
    const BITWISE_OR                     = 52;
    const BITWISE_XOR                    = 53;
    const IN                             = 54;
    const NOT_IN                         = 55;
    const F_IF                           = 56;
    const F_ELT                          = 57;
    const F_COALESCE                     = 58;
    const F_ISNULL                       = 59;
    const F_CONCAT                       = 60;
    const F_SUBSTR                       = 61;
    const F_TRIM                         = 62;
    const F_DATE                         = 63;
    const F_DATE_FORMAT                  = 64;
    const F_CURRENT_DATE                 = 65;
    const F_NOW                          = 66;
    const F_TIME                         = 67;
    const F_TO_DAYS                      = 68;
    const F_FROM_DAYS                    = 69;
    const F_DATE_ADD                     = 70;
    const F_DATE_SUB                     = 71;
    const F_ROUND                        = 72;
    const F_FLOOR                        = 73;
    const F_INET_ATON                    = 74;
    const F_INET_NTOA                    = 75;
    const YY_NO_ACTION = 302;
    const YY_ACCEPT_ACTION = 301;
    const YY_ERROR_ACTION = 300;

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
    const YY_SZ_ACTTAB = 581;
static public $yy_action = array(
 /*     0 */    28,  108,   40,  184,  184,   52,   39,   55,   42,   67,
 /*    10 */    42,    8,  122,   69,   92,  126,    5,   48,  125,  127,
 /*    20 */    63,   58,  167,  165,  158,  106,  131,  109,  108,  103,
 /*    30 */    65,  112,  113,   74,  119,  111,  118,  104,  105,   62,
 /*    40 */    23,   20,   22,   24,   26,   25,   27,   21,   19,   30,
 /*    50 */     4,   71,   60,    1,  114,  115,  116,  117,  137,  120,
 /*    60 */   138,  157,  159,  160,  161,  162,  163,  164,  168,  169,
 /*    70 */   170,  171,  172,  173,    5,   38,    7,   10,  134,   71,
 /*    80 */   167,  165,  158,  106,  130,  109,  108,  103,   65,  112,
 /*    90 */   113,   88,  143,  144,   42,    5,  107,   51,  132,  133,
 /*   100 */    42,  167,  165,  158,  106,   71,  109,  108,  103,   65,
 /*   110 */   112,  113,  114,  115,  116,  117,  137,  120,  138,  157,
 /*   120 */   159,  160,  161,  162,  163,  164,  168,  169,  170,  171,
 /*   130 */   172,  173,  241,  114,  115,  116,  117,  137,  120,  138,
 /*   140 */   157,  159,  160,  161,  162,  163,  164,  168,  169,  170,
 /*   150 */   171,  172,  173,    6,  110,  148,  155,   47,  301,  101,
 /*   160 */    66,  174,   67,    9,  128,  141,  142,   75,  126,   31,
 /*   170 */    49,  125,  127,   63,   13,  135,   47,   17,   79,   14,
 /*   180 */    67,   36,   37,  130,   11,   53,   91,  119,  111,  118,
 /*   190 */   104,  105,   62,   68,   61,   73,   67,  132,  133,  166,
 /*   200 */   156,   64,  126,   32,   49,  125,  127,   63,   90,   41,
 /*   210 */    62,   17,   71,   14,   12,   36,    2,   70,   57,  136,
 /*   220 */   129,  119,  111,  118,  104,  105,   62,   67,   45,   43,
 /*   230 */    44,   71,   72,  126,   33,   49,  125,  127,   63,   59,
 /*   240 */    39,   67,   17,   67,   14,  245,   36,   89,   99,   56,
 /*   250 */    93,  123,  119,  111,  118,  104,  105,   62,  124,   67,
 /*   260 */    71,   50,   71,    3,   42,  126,   31,   49,  125,  127,
 /*   270 */    63,   62,   28,   62,   17,   67,   14,  121,   36,   46,
 /*   280 */    77,   56,   80,   39,  119,  111,  118,  104,  105,   62,
 /*   290 */    54,    8,  245,   67,  245,  245,   78,  245,  245,  126,
 /*   300 */    33,   49,  125,  127,   63,   62,  131,   67,   17,  245,
 /*   310 */    14,  245,   36,   84,  245,  245,   81,  245,  119,  111,
 /*   320 */   118,  104,  105,   62,   67,  245,  245,  245,  245,  245,
 /*   330 */   126,   16,   49,  125,  127,   63,  245,   62,   67,   17,
 /*   340 */    67,   14,  245,   36,  100,  245,   85,  245,  245,  119,
 /*   350 */   111,  118,  104,  105,   62,  245,   67,  245,  245,  245,
 /*   360 */   245,  245,  126,   29,   49,  125,  127,   63,   62,  245,
 /*   370 */    62,   17,  245,   14,  245,   36,  245,  245,  245,  245,
 /*   380 */   245,  119,  111,  118,  104,  105,   62,  245,  245,  245,
 /*   390 */    67,  245,  245,  245,  245,  245,  126,   34,   49,  125,
 /*   400 */   127,   63,  245,  245,   67,   17,  245,   14,  245,   36,
 /*   410 */    87,  245,  245,  245,  245,  119,  111,  118,  104,  105,
 /*   420 */    62,   67,   67,  245,  245,  245,  245,  126,   83,   49,
 /*   430 */   125,  127,   63,  245,   62,   67,   17,  245,   14,  245,
 /*   440 */    35,   86,  245,  245,  245,  245,  119,  111,  118,  104,
 /*   450 */   105,   62,   62,   67,  245,  245,  245,  245,  245,  126,
 /*   460 */   245,   49,  125,  127,   63,   62,  245,  245,   17,  245,
 /*   470 */    15,  245,  245,  245,  245,  245,  245,  245,  119,  111,
 /*   480 */   118,  104,  105,   62,  245,  245,  245,   67,  245,  245,
 /*   490 */   245,  245,  245,  126,  245,   49,  125,  127,   63,  245,
 /*   500 */   245,   67,   18,  146,  245,  245,  245,   76,  245,  245,
 /*   510 */   245,  245,  119,  111,  118,  104,  105,   62,  245,  245,
 /*   520 */   245,  245,  245,  245,  245,  245,  149,  245,  245,  145,
 /*   530 */   140,   62,  245,  245,  245,  147,  150,  151,  152,  153,
 /*   540 */   154,  139,  102,  245,  245,  245,  245,  245,   82,   98,
 /*   550 */    97,   96,   95,   94,  245,  245,  245,  245,  245,  245,
 /*   560 */   245,  245,  245,  245,  245,  130,  245,  245,  245,  245,
 /*   570 */   245,  245,  245,  245,  245,  245,  245,  245,  245,  132,
 /*   580 */   133,
    );
    static public $yy_lookahead = array(
 /*     0 */     2,   30,    3,    4,    5,   82,    7,   82,   85,   81,
 /*    10 */    85,  102,   78,   79,  105,   87,   18,   89,   90,   91,
 /*    20 */    92,   81,   24,   25,   26,   27,  117,   29,   30,   31,
 /*    30 */    32,   33,   34,   83,  106,  107,  108,  109,  110,  111,
 /*    40 */     9,   10,   11,   12,   13,   14,   15,   16,   17,   81,
 /*    50 */     6,  111,   84,   18,   56,   57,   58,   59,   60,   61,
 /*    60 */    62,   63,   64,   65,   66,   67,   68,   69,   70,   71,
 /*    70 */    72,   73,   74,   75,   18,   81,   20,   99,   95,  111,
 /*    80 */    24,   25,   26,   27,   38,   29,   30,   31,   32,   33,
 /*    90 */    34,   82,  114,  115,   85,   18,  111,   82,   52,   53,
 /*   100 */    85,   24,   25,   26,   27,  111,   29,   30,   31,   32,
 /*   110 */    33,   34,   56,   57,   58,   59,   60,   61,   62,   63,
 /*   120 */    64,   65,   66,   67,   68,   69,   70,   71,   72,   73,
 /*   130 */    74,   75,   28,   56,   57,   58,   59,   60,   61,   62,
 /*   140 */    63,   64,   65,   66,   67,   68,   69,   70,   71,   72,
 /*   150 */    73,   74,   75,    5,   31,   39,   40,    1,   77,   78,
 /*   160 */    79,   80,   81,  101,   19,   49,   50,   19,   87,   88,
 /*   170 */    89,   90,   91,   92,    8,   19,    1,   96,  116,   98,
 /*   180 */    81,  100,   81,   38,   97,   28,   87,  106,  107,  108,
 /*   190 */   109,  110,  111,   78,   79,   19,   81,   52,   53,  112,
 /*   200 */   113,   81,   87,   88,   89,   90,   91,   92,   83,    3,
 /*   210 */   111,   96,  111,   98,    8,  100,   18,   83,  103,   54,
 /*   220 */    55,  106,  107,  108,  109,  110,  111,   81,    4,    5,
 /*   230 */    81,  111,   81,   87,   88,   89,   90,   91,   92,   93,
 /*   240 */     7,   81,   96,   81,   98,  118,  100,   87,   86,   87,
 /*   250 */   104,   83,  106,  107,  108,  109,  110,  111,   80,   81,
 /*   260 */   111,   82,  111,    5,   85,   87,   88,   89,   90,   91,
 /*   270 */    92,  111,    2,  111,   96,   81,   98,   19,  100,    3,
 /*   280 */    86,   87,   37,    7,  106,  107,  108,  109,  110,  111,
 /*   290 */    94,  102,  118,   81,  118,  118,   51,  118,  118,   87,
 /*   300 */    88,   89,   90,   91,   92,  111,  117,   81,   96,  118,
 /*   310 */    98,  118,  100,   87,  118,  118,  104,  118,  106,  107,
 /*   320 */   108,  109,  110,  111,   81,  118,  118,  118,  118,  118,
 /*   330 */    87,   88,   89,   90,   91,   92,  118,  111,   81,   96,
 /*   340 */    81,   98,  118,  100,   87,  118,   87,  118,  118,  106,
 /*   350 */   107,  108,  109,  110,  111,  118,   81,  118,  118,  118,
 /*   360 */   118,  118,   87,   88,   89,   90,   91,   92,  111,  118,
 /*   370 */   111,   96,  118,   98,  118,  100,  118,  118,  118,  118,
 /*   380 */   118,  106,  107,  108,  109,  110,  111,  118,  118,  118,
 /*   390 */    81,  118,  118,  118,  118,  118,   87,   88,   89,   90,
 /*   400 */    91,   92,  118,  118,   81,   96,  118,   98,  118,  100,
 /*   410 */    87,  118,  118,  118,  118,  106,  107,  108,  109,  110,
 /*   420 */   111,   81,   81,  118,  118,  118,  118,   87,   87,   89,
 /*   430 */    90,   91,   92,  118,  111,   81,   96,  118,   98,  118,
 /*   440 */   100,   87,  118,  118,  118,  118,  106,  107,  108,  109,
 /*   450 */   110,  111,  111,   81,  118,  118,  118,  118,  118,   87,
 /*   460 */   118,   89,   90,   91,   92,  111,  118,  118,   96,  118,
 /*   470 */    98,  118,  118,  118,  118,  118,  118,  118,  106,  107,
 /*   480 */   108,  109,  110,  111,  118,  118,  118,   81,  118,  118,
 /*   490 */   118,  118,  118,   87,  118,   89,   90,   91,   92,  118,
 /*   500 */   118,   81,   96,    9,  118,  118,  118,   87,  118,  118,
 /*   510 */   118,  118,  106,  107,  108,  109,  110,  111,  118,  118,
 /*   520 */   118,  118,  118,  118,  118,  118,   32,  118,  118,   35,
 /*   530 */    36,  111,  118,  118,  118,   41,   42,   43,   44,   45,
 /*   540 */    46,   47,   48,  118,  118,  118,  118,  118,   21,   22,
 /*   550 */    23,   24,   25,   26,  118,  118,  118,  118,  118,  118,
 /*   560 */   118,  118,  118,  118,  118,   38,  118,  118,  118,  118,
 /*   570 */   118,  118,  118,  118,  118,  118,  118,  118,  118,   52,
 /*   580 */    53,
);
    const YY_SHIFT_USE_DFLT = -30;
    const YY_SHIFT_MAX = 69;
    static public $yy_shift_ofst = array(
 /*     0 */    -2,   -2,   56,   56,   77,   77,   77,   77,   77,   77,
 /*    10 */    77,   77,  -29,  -29,  494,  494,  527,  116,  116,  -29,
 /*    20 */   -29,  -29,  -29,  -29,  -29,  -29,  -29,  -29,  -29,  145,
 /*    30 */    -1,   46,   46,   46,   46,  245,  245,  276,  233,  -29,
 /*    40 */   -29,  -29,  233,  -29,  233,  -29,  -29,  270,  165,  165,
 /*    50 */    44,   44,   44,  -29,   35,   44,   31,  148,  206,  258,
 /*    60 */   224,  156,  104,  198,  166,  123,  175,  157,  176,  175,
);
    const YY_REDUCE_USE_DFLT = -92;
    const YY_REDUCE_MAX = 55;
    static public $yy_reduce_ofst = array(
 /*     0 */    81,  115,  146,  212,  178,  275,  309,  243,  340,  372,
 /*    10 */   406,  -72,  194,  162,  -22,  -22,  -91,   87,   87,  420,
 /*    20 */   354,  341,  323,  259,  257,  226,  160,   99,  -32,  189,
 /*    30 */   179,  189,  189,  189,  189,   62,   62,  -75,  -77,  -60,
 /*    40 */    -6,  120,    9,  151,   15,  101,  149,  -66,  196,  196,
 /*    50 */   168,  134,  125,  -15,  -17,  -50,
);
    static public $yyExpectedTokens = array(
        /* 0 */ array(2, 18, 24, 25, 26, 27, 29, 30, 31, 32, 33, 34, 56, 57, 58, 59, 60, 61, 62, 63, 64, 65, 66, 67, 68, 69, 70, 71, 72, 73, 74, 75, ),
        /* 1 */ array(2, 18, 24, 25, 26, 27, 29, 30, 31, 32, 33, 34, 56, 57, 58, 59, 60, 61, 62, 63, 64, 65, 66, 67, 68, 69, 70, 71, 72, 73, 74, 75, ),
        /* 2 */ array(18, 20, 24, 25, 26, 27, 29, 30, 31, 32, 33, 34, 56, 57, 58, 59, 60, 61, 62, 63, 64, 65, 66, 67, 68, 69, 70, 71, 72, 73, 74, 75, ),
        /* 3 */ array(18, 20, 24, 25, 26, 27, 29, 30, 31, 32, 33, 34, 56, 57, 58, 59, 60, 61, 62, 63, 64, 65, 66, 67, 68, 69, 70, 71, 72, 73, 74, 75, ),
        /* 4 */ array(18, 24, 25, 26, 27, 29, 30, 31, 32, 33, 34, 56, 57, 58, 59, 60, 61, 62, 63, 64, 65, 66, 67, 68, 69, 70, 71, 72, 73, 74, 75, ),
        /* 5 */ array(18, 24, 25, 26, 27, 29, 30, 31, 32, 33, 34, 56, 57, 58, 59, 60, 61, 62, 63, 64, 65, 66, 67, 68, 69, 70, 71, 72, 73, 74, 75, ),
        /* 6 */ array(18, 24, 25, 26, 27, 29, 30, 31, 32, 33, 34, 56, 57, 58, 59, 60, 61, 62, 63, 64, 65, 66, 67, 68, 69, 70, 71, 72, 73, 74, 75, ),
        /* 7 */ array(18, 24, 25, 26, 27, 29, 30, 31, 32, 33, 34, 56, 57, 58, 59, 60, 61, 62, 63, 64, 65, 66, 67, 68, 69, 70, 71, 72, 73, 74, 75, ),
        /* 8 */ array(18, 24, 25, 26, 27, 29, 30, 31, 32, 33, 34, 56, 57, 58, 59, 60, 61, 62, 63, 64, 65, 66, 67, 68, 69, 70, 71, 72, 73, 74, 75, ),
        /* 9 */ array(18, 24, 25, 26, 27, 29, 30, 31, 32, 33, 34, 56, 57, 58, 59, 60, 61, 62, 63, 64, 65, 66, 67, 68, 69, 70, 71, 72, 73, 74, 75, ),
        /* 10 */ array(18, 24, 25, 26, 27, 29, 30, 31, 32, 33, 34, 56, 57, 58, 59, 60, 61, 62, 63, 64, 65, 66, 67, 68, 69, 70, 71, 72, 73, 74, 75, ),
        /* 11 */ array(18, 24, 25, 26, 27, 29, 30, 31, 32, 33, 34, 56, 57, 58, 59, 60, 61, 62, 63, 64, 65, 66, 67, 68, 69, 70, 71, 72, 73, 74, 75, ),
        /* 12 */ array(30, ),
        /* 13 */ array(30, ),
        /* 14 */ array(9, 32, 35, 36, 41, 42, 43, 44, 45, 46, 47, 48, ),
        /* 15 */ array(9, 32, 35, 36, 41, 42, 43, 44, 45, 46, 47, 48, ),
        /* 16 */ array(21, 22, 23, 24, 25, 26, 38, 52, 53, ),
        /* 17 */ array(39, 40, 49, 50, ),
        /* 18 */ array(39, 40, 49, 50, ),
        /* 19 */ array(30, ),
        /* 20 */ array(30, ),
        /* 21 */ array(30, ),
        /* 22 */ array(30, ),
        /* 23 */ array(30, ),
        /* 24 */ array(30, ),
        /* 25 */ array(30, ),
        /* 26 */ array(30, ),
        /* 27 */ array(30, ),
        /* 28 */ array(30, ),
        /* 29 */ array(19, 38, 52, 53, ),
        /* 30 */ array(3, 4, 5, 7, ),
        /* 31 */ array(38, 52, 53, ),
        /* 32 */ array(38, 52, 53, ),
        /* 33 */ array(38, 52, 53, ),
        /* 34 */ array(38, 52, 53, ),
        /* 35 */ array(37, 51, ),
        /* 36 */ array(37, 51, ),
        /* 37 */ array(3, 7, ),
        /* 38 */ array(7, ),
        /* 39 */ array(30, ),
        /* 40 */ array(30, ),
        /* 41 */ array(30, ),
        /* 42 */ array(7, ),
        /* 43 */ array(30, ),
        /* 44 */ array(7, ),
        /* 45 */ array(30, ),
        /* 46 */ array(30, ),
        /* 47 */ array(2, ),
        /* 48 */ array(54, 55, ),
        /* 49 */ array(54, 55, ),
        /* 50 */ array(6, ),
        /* 51 */ array(6, ),
        /* 52 */ array(6, ),
        /* 53 */ array(30, ),
        /* 54 */ array(18, ),
        /* 55 */ array(6, ),
        /* 56 */ array(9, 10, 11, 12, 13, 14, 15, 16, 17, ),
        /* 57 */ array(5, 19, ),
        /* 58 */ array(3, 8, ),
        /* 59 */ array(5, 19, ),
        /* 60 */ array(4, 5, ),
        /* 61 */ array(1, 19, ),
        /* 62 */ array(28, ),
        /* 63 */ array(18, ),
        /* 64 */ array(8, ),
        /* 65 */ array(31, ),
        /* 66 */ array(1, ),
        /* 67 */ array(28, ),
        /* 68 */ array(19, ),
        /* 69 */ array(1, ),
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
        /* 106 */ array(),
        /* 107 */ array(),
        /* 108 */ array(),
        /* 109 */ array(),
        /* 110 */ array(),
        /* 111 */ array(),
        /* 112 */ array(),
        /* 113 */ array(),
        /* 114 */ array(),
        /* 115 */ array(),
        /* 116 */ array(),
        /* 117 */ array(),
        /* 118 */ array(),
        /* 119 */ array(),
        /* 120 */ array(),
        /* 121 */ array(),
        /* 122 */ array(),
        /* 123 */ array(),
        /* 124 */ array(),
        /* 125 */ array(),
        /* 126 */ array(),
        /* 127 */ array(),
        /* 128 */ array(),
        /* 129 */ array(),
        /* 130 */ array(),
        /* 131 */ array(),
        /* 132 */ array(),
        /* 133 */ array(),
        /* 134 */ array(),
        /* 135 */ array(),
        /* 136 */ array(),
        /* 137 */ array(),
        /* 138 */ array(),
        /* 139 */ array(),
        /* 140 */ array(),
        /* 141 */ array(),
        /* 142 */ array(),
        /* 143 */ array(),
        /* 144 */ array(),
        /* 145 */ array(),
        /* 146 */ array(),
        /* 147 */ array(),
        /* 148 */ array(),
        /* 149 */ array(),
        /* 150 */ array(),
        /* 151 */ array(),
        /* 152 */ array(),
        /* 153 */ array(),
        /* 154 */ array(),
        /* 155 */ array(),
        /* 156 */ array(),
        /* 157 */ array(),
        /* 158 */ array(),
        /* 159 */ array(),
        /* 160 */ array(),
        /* 161 */ array(),
        /* 162 */ array(),
        /* 163 */ array(),
        /* 164 */ array(),
        /* 165 */ array(),
        /* 166 */ array(),
        /* 167 */ array(),
        /* 168 */ array(),
        /* 169 */ array(),
        /* 170 */ array(),
        /* 171 */ array(),
        /* 172 */ array(),
        /* 173 */ array(),
        /* 174 */ array(),
);
    static public $yy_default = array(
 /*     0 */   300,  300,  222,  300,  300,  300,  300,  300,  300,  300,
 /*    10 */   300,  300,  300,  300,  213,  214,  300,  211,  212,  300,
 /*    20 */   300,  300,  300,  300,  300,  300,  300,  300,  300,  300,
 /*    30 */   190,  202,  220,  225,  221,  216,  215,  190,  190,  300,
 /*    40 */   300,  300,  189,  300,  190,  300,  300,  300,  210,  209,
 /*    50 */   187,  187,  187,  300,  300,  187,  300,  300,  300,  300,
 /*    60 */   300,  300,  239,  300,  300,  300,  176,  300,  300,  178,
 /*    70 */   183,  241,  185,  219,  182,  217,  201,  192,  272,  256,
 /*    80 */   255,  224,  227,  200,  198,  193,  194,  195,  188,  197,
 /*    90 */   181,  199,  226,  223,  232,  231,  230,  229,  228,  191,
 /*   100 */   196,  175,  269,  244,  236,  237,  238,  240,  243,  242,
 /*   110 */   245,  234,  246,  247,  277,  278,  279,  280,  235,  233,
 /*   120 */   282,  206,  179,  180,  186,  203,  204,  205,  207,  276,
 /*   130 */   257,  258,  273,  274,  208,  218,  275,  281,  283,  268,
 /*   140 */   254,  270,  271,  250,  251,  252,  253,  261,  259,  262,
 /*   150 */   263,  264,  265,  266,  267,  260,  249,  284,  291,  285,
 /*   160 */   286,  287,  288,  289,  290,  292,  248,  293,  294,  295,
 /*   170 */   296,  297,  298,  299,  177,
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
    const YYNOCODE = 119;
    const YYSTACKDEPTH = 1000;
    const YYNSTATE = 175;
    const YYNRULE = 125;
    const YYERRORSYMBOL = 76;
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
    public $yyidx = -1;                    /* Index of top element in stack */
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
  '$',             'UNION',         'SELECT',        'AS_ALIAS',    
  'FROM',          'COMA',          'WHERE',         'JOIN',        
  'ON',            'EQ',            'BELOW',         'BELOW_STRICT',
  'NOT_BELOW',     'NOT_BELOW_STRICT',  'ABOVE',         'ABOVE_STRICT',
  'NOT_ABOVE',     'NOT_ABOVE_STRICT',  'PAR_OPEN',      'PAR_CLOSE',   
  'INTERVAL',      'F_SECOND',      'F_MINUTE',      'F_HOUR',      
  'F_DAY',         'F_MONTH',       'F_YEAR',        'NULL_VAL',    
  'DOT',           'VARNAME',       'NAME',          'NUMVAL',      
  'MATH_MINUS',    'HEXVAL',        'STRVAL',        'REGEXP',      
  'NOT_EQ',        'LOG_AND',       'LOG_OR',        'MATH_DIV',    
  'MATH_MULT',     'MATH_PLUS',     'GT',            'LT',          
  'GE',            'LE',            'LIKE',          'NOT_LIKE',    
  'MATCHES',       'BITWISE_LEFT_SHIFT',  'BITWISE_RIGHT_SHIFT',  'BITWISE_AND', 
  'BITWISE_OR',    'BITWISE_XOR',   'IN',            'NOT_IN',      
  'F_IF',          'F_ELT',         'F_COALESCE',    'F_ISNULL',    
  'F_CONCAT',      'F_SUBSTR',      'F_TRIM',        'F_DATE',      
  'F_DATE_FORMAT',  'F_CURRENT_DATE',  'F_NOW',         'F_TIME',      
  'F_TO_DAYS',     'F_FROM_DAYS',   'F_DATE_ADD',    'F_DATE_SUB',  
  'F_ROUND',       'F_FLOOR',       'F_INET_ATON',   'F_INET_NTOA', 
  'error',         'result',        'union',         'query',       
  'condition',     'class_name',    'join_statement',  'where_statement',
  'class_list',    'join_item',     'join_condition',  'field_id',    
  'expression_prio4',  'expression_basic',  'scalar',        'var_name',    
  'func_name',     'arg_list',      'list_operator',  'list',        
  'expression_prio1',  'operator1',     'expression_prio2',  'operator2',   
  'expression_prio3',  'operator3',     'operator4',     'list_items',  
  'argument',      'interval_unit',  'num_scalar',    'str_scalar',  
  'null_scalar',   'num_value',     'str_value',     'name',        
  'num_operator1',  'bitwise_operator1',  'num_operator2',  'str_operator',
  'bitwise_operator3',  'bitwise_operator4',
    );

    /**
     * For tracing reduce actions, the names of all rules are required.
     * @var array
     */
    static public $yyRuleName = array(
 /*   0 */ "result ::= union",
 /*   1 */ "result ::= query",
 /*   2 */ "result ::= condition",
 /*   3 */ "union ::= query UNION query",
 /*   4 */ "union ::= query UNION union",
 /*   5 */ "query ::= SELECT class_name join_statement where_statement",
 /*   6 */ "query ::= SELECT class_name AS_ALIAS class_name join_statement where_statement",
 /*   7 */ "query ::= SELECT class_list FROM class_name join_statement where_statement",
 /*   8 */ "query ::= SELECT class_list FROM class_name AS_ALIAS class_name join_statement where_statement",
 /*   9 */ "class_list ::= class_name",
 /*  10 */ "class_list ::= class_list COMA class_name",
 /*  11 */ "where_statement ::= WHERE condition",
 /*  12 */ "where_statement ::=",
 /*  13 */ "join_statement ::= join_item join_statement",
 /*  14 */ "join_statement ::= join_item",
 /*  15 */ "join_statement ::=",
 /*  16 */ "join_item ::= JOIN class_name AS_ALIAS class_name ON join_condition",
 /*  17 */ "join_item ::= JOIN class_name ON join_condition",
 /*  18 */ "join_condition ::= field_id EQ field_id",
 /*  19 */ "join_condition ::= field_id BELOW field_id",
 /*  20 */ "join_condition ::= field_id BELOW_STRICT field_id",
 /*  21 */ "join_condition ::= field_id NOT_BELOW field_id",
 /*  22 */ "join_condition ::= field_id NOT_BELOW_STRICT field_id",
 /*  23 */ "join_condition ::= field_id ABOVE field_id",
 /*  24 */ "join_condition ::= field_id ABOVE_STRICT field_id",
 /*  25 */ "join_condition ::= field_id NOT_ABOVE field_id",
 /*  26 */ "join_condition ::= field_id NOT_ABOVE_STRICT field_id",
 /*  27 */ "condition ::= expression_prio4",
 /*  28 */ "expression_basic ::= scalar",
 /*  29 */ "expression_basic ::= field_id",
 /*  30 */ "expression_basic ::= var_name",
 /*  31 */ "expression_basic ::= func_name PAR_OPEN arg_list PAR_CLOSE",
 /*  32 */ "expression_basic ::= PAR_OPEN expression_prio4 PAR_CLOSE",
 /*  33 */ "expression_basic ::= expression_basic list_operator list",
 /*  34 */ "expression_prio1 ::= expression_basic",
 /*  35 */ "expression_prio1 ::= expression_prio1 operator1 expression_basic",
 /*  36 */ "expression_prio2 ::= expression_prio1",
 /*  37 */ "expression_prio2 ::= expression_prio2 operator2 expression_prio1",
 /*  38 */ "expression_prio3 ::= expression_prio2",
 /*  39 */ "expression_prio3 ::= expression_prio3 operator3 expression_prio2",
 /*  40 */ "expression_prio4 ::= expression_prio3",
 /*  41 */ "expression_prio4 ::= expression_prio4 operator4 expression_prio3",
 /*  42 */ "list ::= PAR_OPEN list_items PAR_CLOSE",
 /*  43 */ "list ::= PAR_OPEN query PAR_CLOSE",
 /*  44 */ "list ::= PAR_OPEN union PAR_CLOSE",
 /*  45 */ "list_items ::= expression_prio4",
 /*  46 */ "list_items ::= list_items COMA expression_prio4",
 /*  47 */ "arg_list ::=",
 /*  48 */ "arg_list ::= argument",
 /*  49 */ "arg_list ::= arg_list COMA argument",
 /*  50 */ "argument ::= expression_prio4",
 /*  51 */ "argument ::= INTERVAL expression_prio4 interval_unit",
 /*  52 */ "interval_unit ::= F_SECOND",
 /*  53 */ "interval_unit ::= F_MINUTE",
 /*  54 */ "interval_unit ::= F_HOUR",
 /*  55 */ "interval_unit ::= F_DAY",
 /*  56 */ "interval_unit ::= F_MONTH",
 /*  57 */ "interval_unit ::= F_YEAR",
 /*  58 */ "scalar ::= num_scalar",
 /*  59 */ "scalar ::= str_scalar",
 /*  60 */ "scalar ::= null_scalar",
 /*  61 */ "num_scalar ::= num_value",
 /*  62 */ "str_scalar ::= str_value",
 /*  63 */ "null_scalar ::= NULL_VAL",
 /*  64 */ "field_id ::= name",
 /*  65 */ "field_id ::= class_name DOT name",
 /*  66 */ "class_name ::= name",
 /*  67 */ "var_name ::= VARNAME",
 /*  68 */ "name ::= NAME",
 /*  69 */ "num_value ::= NUMVAL",
 /*  70 */ "num_value ::= MATH_MINUS NUMVAL",
 /*  71 */ "num_value ::= HEXVAL",
 /*  72 */ "str_value ::= STRVAL",
 /*  73 */ "operator1 ::= num_operator1",
 /*  74 */ "operator1 ::= bitwise_operator1",
 /*  75 */ "operator2 ::= num_operator2",
 /*  76 */ "operator2 ::= str_operator",
 /*  77 */ "operator2 ::= REGEXP",
 /*  78 */ "operator2 ::= EQ",
 /*  79 */ "operator2 ::= NOT_EQ",
 /*  80 */ "operator3 ::= LOG_AND",
 /*  81 */ "operator3 ::= bitwise_operator3",
 /*  82 */ "operator4 ::= LOG_OR",
 /*  83 */ "operator4 ::= bitwise_operator4",
 /*  84 */ "num_operator1 ::= MATH_DIV",
 /*  85 */ "num_operator1 ::= MATH_MULT",
 /*  86 */ "num_operator2 ::= MATH_PLUS",
 /*  87 */ "num_operator2 ::= MATH_MINUS",
 /*  88 */ "num_operator2 ::= GT",
 /*  89 */ "num_operator2 ::= LT",
 /*  90 */ "num_operator2 ::= GE",
 /*  91 */ "num_operator2 ::= LE",
 /*  92 */ "str_operator ::= LIKE",
 /*  93 */ "str_operator ::= NOT_LIKE",
 /*  94 */ "str_operator ::= MATCHES",
 /*  95 */ "bitwise_operator1 ::= BITWISE_LEFT_SHIFT",
 /*  96 */ "bitwise_operator1 ::= BITWISE_RIGHT_SHIFT",
 /*  97 */ "bitwise_operator3 ::= BITWISE_AND",
 /*  98 */ "bitwise_operator4 ::= BITWISE_OR",
 /*  99 */ "bitwise_operator4 ::= BITWISE_XOR",
 /* 100 */ "list_operator ::= IN",
 /* 101 */ "list_operator ::= NOT_IN",
 /* 102 */ "func_name ::= F_IF",
 /* 103 */ "func_name ::= F_ELT",
 /* 104 */ "func_name ::= F_COALESCE",
 /* 105 */ "func_name ::= F_ISNULL",
 /* 106 */ "func_name ::= F_CONCAT",
 /* 107 */ "func_name ::= F_SUBSTR",
 /* 108 */ "func_name ::= F_TRIM",
 /* 109 */ "func_name ::= F_DATE",
 /* 110 */ "func_name ::= F_DATE_FORMAT",
 /* 111 */ "func_name ::= F_CURRENT_DATE",
 /* 112 */ "func_name ::= F_NOW",
 /* 113 */ "func_name ::= F_TIME",
 /* 114 */ "func_name ::= F_TO_DAYS",
 /* 115 */ "func_name ::= F_FROM_DAYS",
 /* 116 */ "func_name ::= F_YEAR",
 /* 117 */ "func_name ::= F_MONTH",
 /* 118 */ "func_name ::= F_DAY",
 /* 119 */ "func_name ::= F_DATE_ADD",
 /* 120 */ "func_name ::= F_DATE_SUB",
 /* 121 */ "func_name ::= F_ROUND",
 /* 122 */ "func_name ::= F_FLOOR",
 /* 123 */ "func_name ::= F_INET_ATON",
 /* 124 */ "func_name ::= F_INET_NTOA",
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
#line 30 "..\oql-parser.y"

throw new OQLParserStackOverFlowException($this->m_sSourceQuery, $this->m_iLine, $this->m_iCol);
#line 1186 "..\oql-parser.php"
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
            for ($i = 1; $i <= $this->yyidx; $i++) {
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
  array( 'lhs' => 77, 'rhs' => 1 ),
  array( 'lhs' => 77, 'rhs' => 1 ),
  array( 'lhs' => 77, 'rhs' => 1 ),
  array( 'lhs' => 78, 'rhs' => 3 ),
  array( 'lhs' => 78, 'rhs' => 3 ),
  array( 'lhs' => 79, 'rhs' => 4 ),
  array( 'lhs' => 79, 'rhs' => 6 ),
  array( 'lhs' => 79, 'rhs' => 6 ),
  array( 'lhs' => 79, 'rhs' => 8 ),
  array( 'lhs' => 84, 'rhs' => 1 ),
  array( 'lhs' => 84, 'rhs' => 3 ),
  array( 'lhs' => 83, 'rhs' => 2 ),
  array( 'lhs' => 83, 'rhs' => 0 ),
  array( 'lhs' => 82, 'rhs' => 2 ),
  array( 'lhs' => 82, 'rhs' => 1 ),
  array( 'lhs' => 82, 'rhs' => 0 ),
  array( 'lhs' => 85, 'rhs' => 6 ),
  array( 'lhs' => 85, 'rhs' => 4 ),
  array( 'lhs' => 86, 'rhs' => 3 ),
  array( 'lhs' => 86, 'rhs' => 3 ),
  array( 'lhs' => 86, 'rhs' => 3 ),
  array( 'lhs' => 86, 'rhs' => 3 ),
  array( 'lhs' => 86, 'rhs' => 3 ),
  array( 'lhs' => 86, 'rhs' => 3 ),
  array( 'lhs' => 86, 'rhs' => 3 ),
  array( 'lhs' => 86, 'rhs' => 3 ),
  array( 'lhs' => 86, 'rhs' => 3 ),
  array( 'lhs' => 80, 'rhs' => 1 ),
  array( 'lhs' => 89, 'rhs' => 1 ),
  array( 'lhs' => 89, 'rhs' => 1 ),
  array( 'lhs' => 89, 'rhs' => 1 ),
  array( 'lhs' => 89, 'rhs' => 4 ),
  array( 'lhs' => 89, 'rhs' => 3 ),
  array( 'lhs' => 89, 'rhs' => 3 ),
  array( 'lhs' => 96, 'rhs' => 1 ),
  array( 'lhs' => 96, 'rhs' => 3 ),
  array( 'lhs' => 98, 'rhs' => 1 ),
  array( 'lhs' => 98, 'rhs' => 3 ),
  array( 'lhs' => 100, 'rhs' => 1 ),
  array( 'lhs' => 100, 'rhs' => 3 ),
  array( 'lhs' => 88, 'rhs' => 1 ),
  array( 'lhs' => 88, 'rhs' => 3 ),
  array( 'lhs' => 95, 'rhs' => 3 ),
  array( 'lhs' => 95, 'rhs' => 3 ),
  array( 'lhs' => 95, 'rhs' => 3 ),
  array( 'lhs' => 103, 'rhs' => 1 ),
  array( 'lhs' => 103, 'rhs' => 3 ),
  array( 'lhs' => 93, 'rhs' => 0 ),
  array( 'lhs' => 93, 'rhs' => 1 ),
  array( 'lhs' => 93, 'rhs' => 3 ),
  array( 'lhs' => 104, 'rhs' => 1 ),
  array( 'lhs' => 104, 'rhs' => 3 ),
  array( 'lhs' => 105, 'rhs' => 1 ),
  array( 'lhs' => 105, 'rhs' => 1 ),
  array( 'lhs' => 105, 'rhs' => 1 ),
  array( 'lhs' => 105, 'rhs' => 1 ),
  array( 'lhs' => 105, 'rhs' => 1 ),
  array( 'lhs' => 105, 'rhs' => 1 ),
  array( 'lhs' => 90, 'rhs' => 1 ),
  array( 'lhs' => 90, 'rhs' => 1 ),
  array( 'lhs' => 90, 'rhs' => 1 ),
  array( 'lhs' => 106, 'rhs' => 1 ),
  array( 'lhs' => 107, 'rhs' => 1 ),
  array( 'lhs' => 108, 'rhs' => 1 ),
  array( 'lhs' => 87, 'rhs' => 1 ),
  array( 'lhs' => 87, 'rhs' => 3 ),
  array( 'lhs' => 81, 'rhs' => 1 ),
  array( 'lhs' => 91, 'rhs' => 1 ),
  array( 'lhs' => 111, 'rhs' => 1 ),
  array( 'lhs' => 109, 'rhs' => 1 ),
  array( 'lhs' => 109, 'rhs' => 2 ),
  array( 'lhs' => 109, 'rhs' => 1 ),
  array( 'lhs' => 110, 'rhs' => 1 ),
  array( 'lhs' => 97, 'rhs' => 1 ),
  array( 'lhs' => 97, 'rhs' => 1 ),
  array( 'lhs' => 99, 'rhs' => 1 ),
  array( 'lhs' => 99, 'rhs' => 1 ),
  array( 'lhs' => 99, 'rhs' => 1 ),
  array( 'lhs' => 99, 'rhs' => 1 ),
  array( 'lhs' => 99, 'rhs' => 1 ),
  array( 'lhs' => 101, 'rhs' => 1 ),
  array( 'lhs' => 101, 'rhs' => 1 ),
  array( 'lhs' => 102, 'rhs' => 1 ),
  array( 'lhs' => 102, 'rhs' => 1 ),
  array( 'lhs' => 112, 'rhs' => 1 ),
  array( 'lhs' => 112, 'rhs' => 1 ),
  array( 'lhs' => 114, 'rhs' => 1 ),
  array( 'lhs' => 114, 'rhs' => 1 ),
  array( 'lhs' => 114, 'rhs' => 1 ),
  array( 'lhs' => 114, 'rhs' => 1 ),
  array( 'lhs' => 114, 'rhs' => 1 ),
  array( 'lhs' => 114, 'rhs' => 1 ),
  array( 'lhs' => 115, 'rhs' => 1 ),
  array( 'lhs' => 115, 'rhs' => 1 ),
  array( 'lhs' => 115, 'rhs' => 1 ),
  array( 'lhs' => 113, 'rhs' => 1 ),
  array( 'lhs' => 113, 'rhs' => 1 ),
  array( 'lhs' => 116, 'rhs' => 1 ),
  array( 'lhs' => 117, 'rhs' => 1 ),
  array( 'lhs' => 117, 'rhs' => 1 ),
  array( 'lhs' => 94, 'rhs' => 1 ),
  array( 'lhs' => 94, 'rhs' => 1 ),
  array( 'lhs' => 92, 'rhs' => 1 ),
  array( 'lhs' => 92, 'rhs' => 1 ),
  array( 'lhs' => 92, 'rhs' => 1 ),
  array( 'lhs' => 92, 'rhs' => 1 ),
  array( 'lhs' => 92, 'rhs' => 1 ),
  array( 'lhs' => 92, 'rhs' => 1 ),
  array( 'lhs' => 92, 'rhs' => 1 ),
  array( 'lhs' => 92, 'rhs' => 1 ),
  array( 'lhs' => 92, 'rhs' => 1 ),
  array( 'lhs' => 92, 'rhs' => 1 ),
  array( 'lhs' => 92, 'rhs' => 1 ),
  array( 'lhs' => 92, 'rhs' => 1 ),
  array( 'lhs' => 92, 'rhs' => 1 ),
  array( 'lhs' => 92, 'rhs' => 1 ),
  array( 'lhs' => 92, 'rhs' => 1 ),
  array( 'lhs' => 92, 'rhs' => 1 ),
  array( 'lhs' => 92, 'rhs' => 1 ),
  array( 'lhs' => 92, 'rhs' => 1 ),
  array( 'lhs' => 92, 'rhs' => 1 ),
  array( 'lhs' => 92, 'rhs' => 1 ),
  array( 'lhs' => 92, 'rhs' => 1 ),
  array( 'lhs' => 92, 'rhs' => 1 ),
  array( 'lhs' => 92, 'rhs' => 1 ),
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
        2 => 0,
        3 => 3,
        4 => 3,
        5 => 5,
        6 => 6,
        7 => 7,
        8 => 8,
        9 => 9,
        45 => 9,
        48 => 9,
        10 => 10,
        46 => 10,
        49 => 10,
        11 => 11,
        12 => 12,
        15 => 12,
        13 => 13,
        14 => 14,
        16 => 16,
        17 => 17,
        18 => 18,
        19 => 19,
        20 => 20,
        21 => 21,
        22 => 22,
        23 => 23,
        24 => 24,
        25 => 25,
        26 => 26,
        27 => 27,
        28 => 27,
        29 => 27,
        30 => 27,
        34 => 27,
        36 => 27,
        38 => 27,
        40 => 27,
        50 => 27,
        52 => 27,
        53 => 27,
        54 => 27,
        55 => 27,
        56 => 27,
        57 => 27,
        58 => 27,
        59 => 27,
        60 => 27,
        31 => 31,
        32 => 32,
        33 => 33,
        35 => 33,
        39 => 33,
        41 => 33,
        37 => 37,
        42 => 42,
        43 => 43,
        44 => 43,
        47 => 47,
        51 => 51,
        61 => 61,
        62 => 61,
        63 => 63,
        64 => 64,
        65 => 65,
        66 => 66,
        102 => 66,
        103 => 66,
        104 => 66,
        105 => 66,
        106 => 66,
        107 => 66,
        108 => 66,
        109 => 66,
        110 => 66,
        111 => 66,
        112 => 66,
        113 => 66,
        114 => 66,
        115 => 66,
        116 => 66,
        117 => 66,
        118 => 66,
        119 => 66,
        120 => 66,
        121 => 66,
        122 => 66,
        123 => 66,
        124 => 66,
        67 => 67,
        68 => 68,
        69 => 69,
        70 => 70,
        71 => 71,
        72 => 72,
        73 => 73,
        74 => 73,
        75 => 73,
        76 => 73,
        77 => 73,
        78 => 73,
        79 => 73,
        80 => 73,
        81 => 73,
        82 => 73,
        83 => 73,
        84 => 73,
        85 => 73,
        86 => 73,
        87 => 73,
        88 => 73,
        89 => 73,
        90 => 73,
        91 => 73,
        92 => 73,
        93 => 73,
        94 => 73,
        95 => 73,
        96 => 73,
        97 => 73,
        98 => 73,
        99 => 73,
        100 => 73,
        101 => 73,
    );
    /* Beginning here are the reduction cases.  A typical example
    ** follows:
    **  #line <lineno> <grammarfile>
    **   function yy_r0($yymsp){ ... }           // User supplied code
    **  #line <lineno> <thisfile>
    */
#line 37 "..\oql-parser.y"
    function yy_r0(){ $this->my_result = $this->yystack[$this->yyidx + 0]->minor;     }
#line 1488 "..\oql-parser.php"
#line 41 "..\oql-parser.y"
    function yy_r3(){
	$this->_retvalue = new OqlUnionQuery($this->yystack[$this->yyidx + -2]->minor, $this->yystack[$this->yyidx + 0]->minor);
    }
#line 1493 "..\oql-parser.php"
#line 48 "..\oql-parser.y"
    function yy_r5(){
	$this->_retvalue = new OqlObjectQuery($this->yystack[$this->yyidx + -2]->minor, $this->yystack[$this->yyidx + -2]->minor, $this->yystack[$this->yyidx + 0]->minor, $this->yystack[$this->yyidx + -1]->minor, array($this->yystack[$this->yyidx + -2]->minor));
    }
#line 1498 "..\oql-parser.php"
#line 51 "..\oql-parser.y"
    function yy_r6(){
	$this->_retvalue = new OqlObjectQuery($this->yystack[$this->yyidx + -4]->minor, $this->yystack[$this->yyidx + -2]->minor, $this->yystack[$this->yyidx + 0]->minor, $this->yystack[$this->yyidx + -1]->minor, array($this->yystack[$this->yyidx + -2]->minor));
    }
#line 1503 "..\oql-parser.php"
#line 55 "..\oql-parser.y"
    function yy_r7(){
	$this->_retvalue = new OqlObjectQuery($this->yystack[$this->yyidx + -2]->minor, $this->yystack[$this->yyidx + -2]->minor, $this->yystack[$this->yyidx + 0]->minor, $this->yystack[$this->yyidx + -1]->minor, $this->yystack[$this->yyidx + -4]->minor);
    }
#line 1508 "..\oql-parser.php"
#line 58 "..\oql-parser.y"
    function yy_r8(){
	$this->_retvalue = new OqlObjectQuery($this->yystack[$this->yyidx + -4]->minor, $this->yystack[$this->yyidx + -2]->minor, $this->yystack[$this->yyidx + 0]->minor, $this->yystack[$this->yyidx + -1]->minor, $this->yystack[$this->yyidx + -6]->minor);
    }
#line 1513 "..\oql-parser.php"
#line 63 "..\oql-parser.y"
    function yy_r9(){
	$this->_retvalue = array($this->yystack[$this->yyidx + 0]->minor);
    }
#line 1518 "..\oql-parser.php"
#line 66 "..\oql-parser.y"
    function yy_r10(){
	array_push($this->yystack[$this->yyidx + -2]->minor, $this->yystack[$this->yyidx + 0]->minor);
	$this->_retvalue = $this->yystack[$this->yyidx + -2]->minor;
    }
#line 1524 "..\oql-parser.php"
#line 71 "..\oql-parser.y"
    function yy_r11(){ $this->_retvalue = $this->yystack[$this->yyidx + 0]->minor;    }
#line 1527 "..\oql-parser.php"
#line 72 "..\oql-parser.y"
    function yy_r12(){ $this->_retvalue = null;    }
#line 1530 "..\oql-parser.php"
#line 74 "..\oql-parser.y"
    function yy_r13(){
	// insert the join statement on top of the existing list
	array_unshift($this->yystack[$this->yyidx + 0]->minor, $this->yystack[$this->yyidx + -1]->minor);
	// and return the updated array
	$this->_retvalue = $this->yystack[$this->yyidx + 0]->minor;
    }
#line 1538 "..\oql-parser.php"
#line 80 "..\oql-parser.y"
    function yy_r14(){
	$this->_retvalue = Array($this->yystack[$this->yyidx + 0]->minor);
    }
#line 1543 "..\oql-parser.php"
#line 86 "..\oql-parser.y"
    function yy_r16(){
	// create an array with one single item
	$this->_retvalue = new OqlJoinSpec($this->yystack[$this->yyidx + -4]->minor, $this->yystack[$this->yyidx + -2]->minor, $this->yystack[$this->yyidx + 0]->minor);
    }
#line 1549 "..\oql-parser.php"
#line 91 "..\oql-parser.y"
    function yy_r17(){
	// create an array with one single item
	$this->_retvalue = new OqlJoinSpec($this->yystack[$this->yyidx + -2]->minor, $this->yystack[$this->yyidx + -2]->minor, $this->yystack[$this->yyidx + 0]->minor);
    }
#line 1555 "..\oql-parser.php"
#line 96 "..\oql-parser.y"
    function yy_r18(){ $this->_retvalue = new BinaryOqlExpression($this->yystack[$this->yyidx + -2]->minor, '=', $this->yystack[$this->yyidx + 0]->minor);     }
#line 1558 "..\oql-parser.php"
#line 97 "..\oql-parser.y"
    function yy_r19(){ $this->_retvalue = new BinaryOqlExpression($this->yystack[$this->yyidx + -2]->minor, 'BELOW', $this->yystack[$this->yyidx + 0]->minor);     }
#line 1561 "..\oql-parser.php"
#line 98 "..\oql-parser.y"
    function yy_r20(){ $this->_retvalue = new BinaryOqlExpression($this->yystack[$this->yyidx + -2]->minor, 'BELOW_STRICT', $this->yystack[$this->yyidx + 0]->minor);     }
#line 1564 "..\oql-parser.php"
#line 99 "..\oql-parser.y"
    function yy_r21(){ $this->_retvalue = new BinaryOqlExpression($this->yystack[$this->yyidx + -2]->minor, 'NOT_BELOW', $this->yystack[$this->yyidx + 0]->minor);     }
#line 1567 "..\oql-parser.php"
#line 100 "..\oql-parser.y"
    function yy_r22(){ $this->_retvalue = new BinaryOqlExpression($this->yystack[$this->yyidx + -2]->minor, 'NOT_BELOW_STRICT', $this->yystack[$this->yyidx + 0]->minor);     }
#line 1570 "..\oql-parser.php"
#line 101 "..\oql-parser.y"
    function yy_r23(){ $this->_retvalue = new BinaryOqlExpression($this->yystack[$this->yyidx + -2]->minor, 'ABOVE', $this->yystack[$this->yyidx + 0]->minor);     }
#line 1573 "..\oql-parser.php"
#line 102 "..\oql-parser.y"
    function yy_r24(){ $this->_retvalue = new BinaryOqlExpression($this->yystack[$this->yyidx + -2]->minor, 'ABOVE_STRICT', $this->yystack[$this->yyidx + 0]->minor);     }
#line 1576 "..\oql-parser.php"
#line 103 "..\oql-parser.y"
    function yy_r25(){ $this->_retvalue = new BinaryOqlExpression($this->yystack[$this->yyidx + -2]->minor, 'NOT_ABOVE', $this->yystack[$this->yyidx + 0]->minor);     }
#line 1579 "..\oql-parser.php"
#line 104 "..\oql-parser.y"
    function yy_r26(){ $this->_retvalue = new BinaryOqlExpression($this->yystack[$this->yyidx + -2]->minor, 'NOT_ABOVE_STRICT', $this->yystack[$this->yyidx + 0]->minor);     }
#line 1582 "..\oql-parser.php"
#line 106 "..\oql-parser.y"
    function yy_r27(){ $this->_retvalue = $this->yystack[$this->yyidx + 0]->minor;     }
#line 1585 "..\oql-parser.php"
#line 111 "..\oql-parser.y"
    function yy_r31(){ $this->_retvalue = new FunctionOqlExpression($this->yystack[$this->yyidx + -3]->minor, $this->yystack[$this->yyidx + -1]->minor);     }
#line 1588 "..\oql-parser.php"
#line 112 "..\oql-parser.y"
    function yy_r32(){ $this->_retvalue = $this->yystack[$this->yyidx + -1]->minor;     }
#line 1591 "..\oql-parser.php"
#line 113 "..\oql-parser.y"
    function yy_r33(){ $this->_retvalue = new BinaryOqlExpression($this->yystack[$this->yyidx + -2]->minor, $this->yystack[$this->yyidx + -1]->minor, $this->yystack[$this->yyidx + 0]->minor);     }
#line 1594 "..\oql-parser.php"
#line 119 "..\oql-parser.y"
    function yy_r37(){
    if ($this->yystack[$this->yyidx + -1]->minor == 'MATCHES')
    {
        $this->_retvalue = new MatchOqlExpression($this->yystack[$this->yyidx + -2]->minor, $this->yystack[$this->yyidx + 0]->minor);
    }
    else
    {
        $this->_retvalue = new BinaryOqlExpression($this->yystack[$this->yyidx + -2]->minor, $this->yystack[$this->yyidx + -1]->minor, $this->yystack[$this->yyidx + 0]->minor);
    }
    }
#line 1606 "..\oql-parser.php"
#line 136 "..\oql-parser.y"
    function yy_r42(){
	$this->_retvalue = new ListOqlExpression($this->yystack[$this->yyidx + -1]->minor);
    }
#line 1611 "..\oql-parser.php"
#line 139 "..\oql-parser.y"
    function yy_r43(){
	$this->_retvalue = new NestedQueryOqlExpression($this->yystack[$this->yyidx + -1]->minor);
    }
#line 1616 "..\oql-parser.php"
#line 154 "..\oql-parser.y"
    function yy_r47(){
	$this->_retvalue = array();
    }
#line 1621 "..\oql-parser.php"
#line 165 "..\oql-parser.y"
    function yy_r51(){ $this->_retvalue = new IntervalOqlExpression($this->yystack[$this->yyidx + -1]->minor, $this->yystack[$this->yyidx + 0]->minor);     }
#line 1624 "..\oql-parser.php"
#line 178 "..\oql-parser.y"
    function yy_r61(){ $this->_retvalue = new ScalarOqlExpression($this->yystack[$this->yyidx + 0]->minor);     }
#line 1627 "..\oql-parser.php"
#line 180 "..\oql-parser.y"
    function yy_r63(){ $this->_retvalue = new ScalarOqlExpression(null);     }
#line 1630 "..\oql-parser.php"
#line 182 "..\oql-parser.y"
    function yy_r64(){ $this->_retvalue = new FieldOqlExpression($this->yystack[$this->yyidx + 0]->minor);     }
#line 1633 "..\oql-parser.php"
#line 183 "..\oql-parser.y"
    function yy_r65(){ $this->_retvalue = new FieldOqlExpression($this->yystack[$this->yyidx + 0]->minor, $this->yystack[$this->yyidx + -2]->minor);     }
#line 1636 "..\oql-parser.php"
#line 184 "..\oql-parser.y"
    function yy_r66(){ $this->_retvalue=$this->yystack[$this->yyidx + 0]->minor;     }
#line 1639 "..\oql-parser.php"
#line 187 "..\oql-parser.y"
    function yy_r67(){ $this->_retvalue = new VariableOqlExpression(substr($this->yystack[$this->yyidx + 0]->minor, 1));     }
#line 1642 "..\oql-parser.php"
#line 189 "..\oql-parser.y"
    function yy_r68(){
	if ($this->yystack[$this->yyidx + 0]->minor[0] == '`')
	{
		$name = substr($this->yystack[$this->yyidx + 0]->minor, 1, strlen($this->yystack[$this->yyidx + 0]->minor) - 2);
	}
	else
	{
		$name = $this->yystack[$this->yyidx + 0]->minor;
	}
	$this->_retvalue = new OqlName($name, $this->m_iColPrev);
    }
#line 1655 "..\oql-parser.php"
#line 200 "..\oql-parser.y"
    function yy_r69(){$this->_retvalue=(int)$this->yystack[$this->yyidx + 0]->minor;    }
#line 1658 "..\oql-parser.php"
#line 201 "..\oql-parser.y"
    function yy_r70(){$this->_retvalue=(int)-$this->yystack[$this->yyidx + 0]->minor;    }
#line 1661 "..\oql-parser.php"
#line 202 "..\oql-parser.y"
    function yy_r71(){$this->_retvalue=new OqlHexValue($this->yystack[$this->yyidx + 0]->minor);    }
#line 1664 "..\oql-parser.php"
#line 203 "..\oql-parser.y"
    function yy_r72(){$this->_retvalue=stripslashes(substr($this->yystack[$this->yyidx + 0]->minor, 1, strlen($this->yystack[$this->yyidx + 0]->minor) - 2));    }
#line 1667 "..\oql-parser.php"
#line 206 "..\oql-parser.y"
    function yy_r73(){$this->_retvalue=$this->yystack[$this->yyidx + 0]->minor;    }
#line 1670 "..\oql-parser.php"

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
        for ($i = $yysize; $i; $i--) {
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
#line 33 "..\oql-parser.y"

throw new OQLParserParseFailureException($this->m_sSourceQuery, $this->m_iLine, $this->m_iCol);
#line 1775 "..\oql-parser.php"
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
#line 25 "..\oql-parser.y"
 
throw new OQLParserSyntaxErrorException($this->m_sSourceQuery, $this->m_iLine, $this->m_iCol, $this->tokenName($yymajor), $TOKEN);
#line 1791 "..\oql-parser.php"
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
     * @param int   $yymajor      the token number
     * @param mixed $yytokenvalue the token value
     * @param mixed ...           any extra arguments that should be passed to handlers
     *
     * @return void
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
            fprintf(
                self::$yyTraceFILE,
                "%sInput %s\n",
                self::$yyTracePrompt,
                self::$yyTokenName[$yymajor]
            );
        }
        
        do {
            $yyact = $this->yy_find_shift_action($yymajor);
            if ($yymajor < self::YYERRORSYMBOL
                && !$this->yy_is_expected_token($yymajor)
            ) {
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
                    fprintf(
                        self::$yyTraceFILE,
                        "%sSyntax Error!\n",
                        self::$yyTracePrompt
                    );
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
                    if ($yymx == self::YYERRORSYMBOL || $yyerrorhit ) {
                        if (self::$yyTraceFILE) {
                            fprintf(
                                self::$yyTraceFILE,
                                "%sDiscard input token %s\n",
                                self::$yyTracePrompt,
                                self::$yyTokenName[$yymajor]
                            );
                        }
                        $this->yy_destructor($yymajor, $yytokenvalue);
                        $yymajor = self::YYNOCODE;
                    } else {
                        while ($this->yyidx >= 0
                            && $yymx != self::YYERRORSYMBOL
                            && ($yyact = $this->yy_find_shift_action(self::YYERRORSYMBOL)) >= self::YYNSTATE
                        ) {
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
}
#line 271 "..\oql-parser.y"


class OQLParserException extends OQLException
{
	public function __construct($sIssue, $sInput, $iLine, $iCol, $sTokenValue)
	{
		parent::__construct($sIssue, $sInput, $iLine, $iCol, $sTokenValue);
	}
}

class OQLParserSyntaxErrorException extends OQLParserException
{
	public function __construct($sInput, $iLine, $iCol, $sTokenName, $sTokenValue)
	{
		$sIssue = "Unexpected token $sTokenName";

		parent::__construct($sIssue, $sInput, $iLine, $iCol, $sTokenValue);
	}
}

class OQLParserStackOverFlowException extends OQLParserException
{
	public function __construct($sInput, $iLine, $iCol)
	{
		$sIssue = "Stack overflow";

		parent::__construct($sIssue, $sInput, $iLine, $iCol, '');
	}
}

class OQLParserParseFailureException extends OQLParserException
{
	public function __construct($sInput, $iLine, $iCol)
	{
		$sIssue = "Unexpected token $sTokenName";

		parent::__construct($sIssue, $sInput, $iLine, $iCol, '');
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
	protected $m_iColPrev; // this is the interesting one, because the parser will reduce on the next token
	protected $m_sSourceQuery;

	public function __construct($sQuery)
	{
		$this->m_iLine = 0;
		$this->m_iCol = 0;
		$this->m_iColPrev = 0;
		$this->m_sSourceQuery = $sQuery;
		// no constructor - parent::__construct();
	}
	
	public function doParse($token, $value, $iCurrPosition = 0)
	{
		$this->m_iColPrev = $this->m_iCol;
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

#line 2052 "..\oql-parser.php"

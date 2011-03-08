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
#line 24 "oql-parser.y"
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
    const FROM                           =  3;
    const COMA                           =  4;
    const WHERE                          =  5;
    const JOIN                           =  6;
    const ON                             =  7;
    const EQ                             =  8;
    const PAR_OPEN                       =  9;
    const PAR_CLOSE                      = 10;
    const INTERVAL                       = 11;
    const F_SECOND                       = 12;
    const F_MINUTE                       = 13;
    const F_HOUR                         = 14;
    const F_DAY                          = 15;
    const F_MONTH                        = 16;
    const F_YEAR                         = 17;
    const DOT                            = 18;
    const VARNAME                        = 19;
    const NAME                           = 20;
    const NUMVAL                         = 21;
    const STRVAL                         = 22;
    const NOT_EQ                         = 23;
    const LOG_AND                        = 24;
    const LOG_OR                         = 25;
    const MATH_DIV                       = 26;
    const MATH_MULT                      = 27;
    const MATH_PLUS                      = 28;
    const MATH_MINUS                     = 29;
    const GT                             = 30;
    const LT                             = 31;
    const GE                             = 32;
    const LE                             = 33;
    const LIKE                           = 34;
    const NOT_LIKE                       = 35;
    const IN                             = 36;
    const NOT_IN                         = 37;
    const F_IF                           = 38;
    const F_ELT                          = 39;
    const F_COALESCE                     = 40;
    const F_CONCAT                       = 41;
    const F_SUBSTR                       = 42;
    const F_TRIM                         = 43;
    const F_DATE                         = 44;
    const F_DATE_FORMAT                  = 45;
    const F_CURRENT_DATE                 = 46;
    const F_NOW                          = 47;
    const F_TIME                         = 48;
    const F_TO_DAYS                      = 49;
    const F_FROM_DAYS                    = 50;
    const F_DATE_ADD                     = 51;
    const F_DATE_SUB                     = 52;
    const F_ROUND                        = 53;
    const F_FLOOR                        = 54;
    const F_INET_ATON                    = 55;
    const F_INET_NTOA                    = 56;
    const YY_NO_ACTION = 234;
    const YY_ACCEPT_ACTION = 233;
    const YY_ERROR_ACTION = 232;

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
    const YY_SZ_ACTTAB = 425;
static public $yy_action = array(
 /*     0 */    16,   55,   29,  141,  141,   69,   27,   96,    7,   33,
 /*    10 */    95,  100,   53,  105,  132,  133,  129,    5,   82,   81,
 /*    20 */    86,   87,   10,   60,   23,   25,   94,   93,   85,   84,
 /*    30 */    51,   19,   49,    8,   50,  114,  115,   92,   91,   90,
 /*    40 */    88,   89,  107,  108,  127,  126,  125,  123,  124,  128,
 /*    50 */   131,  130,  122,  121,  113,  112,    7,  120,    6,    2,
 /*    60 */    63,   63,  132,  133,  129,  101,   82,   81,   86,   87,
 /*    70 */    38,    9,  119,   24,  102,  103,   54,  118,  116,  117,
 /*    80 */   134,   65,   56,   61,   58,   92,   91,   90,   88,   89,
 /*    90 */   107,  108,  127,  126,  125,  123,  124,  128,  131,  130,
 /*   100 */   122,  121,  113,  112,    7,   63,  106,    3,   26,   59,
 /*   110 */   132,  133,  129,   12,   82,   81,   86,   87,   11,   70,
 /*   120 */    42,  105,   24,   24,   80,  110,   30,   68,   31,   28,
 /*   130 */    27,   20,  111,   92,   91,   90,   88,   89,  107,  108,
 /*   140 */   127,  126,  125,  123,  124,  128,  131,  130,  122,  121,
 /*   150 */   113,  112,  233,  109,   99,   55,   63,   63,   63,   46,
 /*   160 */    63,   96,   43,   34,   95,  100,   53,   67,    8,   13,
 /*   170 */    22,   79,   15,   41,   40,   44,   24,   83,   24,   55,
 /*   180 */    94,   93,   85,   84,   51,   96,   35,   34,   95,  100,
 /*   190 */    53,   47,  104,   17,   22,   55,   15,    4,   40,   98,
 /*   200 */    62,   52,   77,   55,   94,   93,   85,   84,   51,   96,
 /*   210 */    35,   34,   95,  100,   53,  186,    1,   27,   22,   81,
 /*   220 */    15,   71,   40,   45,   51,  193,   57,   55,   94,   93,
 /*   230 */    85,   84,   51,   96,   39,   34,   95,  100,   53,  193,
 /*   240 */   193,  193,   22,  193,   15,  193,   40,  193,  193,   48,
 /*   250 */    97,   55,   94,   93,   85,   84,   51,   96,   43,   34,
 /*   260 */    95,  100,   53,  193,  193,  193,   22,   55,   15,  193,
 /*   270 */    40,  193,   66,   52,  193,   55,   94,   93,   85,   84,
 /*   280 */    51,   96,   18,   34,   95,  100,   53,  193,  193,  193,
 /*   290 */    22,   55,   15,  193,   40,  193,   51,   64,  193,   55,
 /*   300 */    94,   93,   85,   84,   51,   96,   36,   34,   95,  100,
 /*   310 */    53,  193,  193,  193,   22,  193,   15,  193,   40,  193,
 /*   320 */    51,  193,  193,   55,   94,   93,   85,   84,   51,   96,
 /*   330 */    32,   34,   95,  100,   53,  193,  193,  193,   22,  193,
 /*   340 */    15,  193,   40,  193,  193,  193,  193,   55,   94,   93,
 /*   350 */    85,   84,   51,   96,  193,   34,   95,  100,   53,  193,
 /*   360 */   193,  193,   22,  193,   15,  193,   37,  193,  193,  193,
 /*   370 */   193,   55,   94,   93,   85,   84,   51,   96,  193,   34,
 /*   380 */    95,  100,   53,  193,  193,  193,   22,  193,   14,   74,
 /*   390 */    73,   72,   75,   76,   78,   55,   94,   93,   85,   84,
 /*   400 */    51,   96,  105,   34,   95,  100,   53,  193,  193,  193,
 /*   410 */    21,  193,  193,  193,  193,  193,  193,  193,  193,  193,
 /*   420 */    94,   93,   85,   84,   51,
    );
    static public $yy_lookahead = array(
 /*     0 */     1,   61,    2,    3,    4,   63,    6,   67,    9,   69,
 /*    10 */    70,   71,   72,   25,   15,   16,   17,    4,   19,   20,
 /*    20 */    21,   22,   79,   10,    3,    4,   86,   87,   88,   89,
 /*    30 */    90,   61,   61,   82,   64,   92,   93,   38,   39,   40,
 /*    40 */    41,   42,   43,   44,   45,   46,   47,   48,   49,   50,
 /*    50 */    51,   52,   53,   54,   55,   56,    9,    8,   11,    4,
 /*    60 */    90,   90,   15,   16,   17,   10,   19,   20,   21,   22,
 /*    70 */    62,   81,   23,   65,   36,   37,   61,   28,   29,   30,
 /*    80 */    31,   32,   33,   34,   35,   38,   39,   40,   41,   42,
 /*    90 */    43,   44,   45,   46,   47,   48,   49,   50,   51,   52,
 /*   100 */    53,   54,   55,   56,    9,   90,   10,    5,    2,   24,
 /*   110 */    15,   16,   17,    7,   19,   20,   21,   22,   77,   62,
 /*   120 */    62,   25,   65,   65,   26,   27,    2,   61,   61,   61,
 /*   130 */     6,   61,   91,   38,   39,   40,   41,   42,   43,   44,
 /*   140 */    45,   46,   47,   48,   49,   50,   51,   52,   53,   54,
 /*   150 */    55,   56,   58,   59,   60,   61,   90,   90,   90,   18,
 /*   160 */    90,   67,   68,   69,   70,   71,   72,   63,   82,    7,
 /*   170 */    76,   85,   78,   62,   80,   62,   65,   90,   65,   61,
 /*   180 */    86,   87,   88,   89,   90,   67,   68,   69,   70,   71,
 /*   190 */    72,   73,   75,    8,   76,   61,   78,    9,   80,   63,
 /*   200 */    66,   67,   84,   61,   86,   87,   88,   89,   90,   67,
 /*   210 */    68,   69,   70,   71,   72,   18,    9,    6,   76,   20,
 /*   220 */    78,   63,   80,   74,   90,   94,   84,   61,   86,   87,
 /*   230 */    88,   89,   90,   67,   68,   69,   70,   71,   72,   94,
 /*   240 */    94,   94,   76,   94,   78,   94,   80,   94,   94,   83,
 /*   250 */    60,   61,   86,   87,   88,   89,   90,   67,   68,   69,
 /*   260 */    70,   71,   72,   94,   94,   94,   76,   61,   78,   94,
 /*   270 */    80,   94,   66,   67,   94,   61,   86,   87,   88,   89,
 /*   280 */    90,   67,   68,   69,   70,   71,   72,   94,   94,   94,
 /*   290 */    76,   61,   78,   94,   80,   94,   90,   67,   94,   61,
 /*   300 */    86,   87,   88,   89,   90,   67,   68,   69,   70,   71,
 /*   310 */    72,   94,   94,   94,   76,   94,   78,   94,   80,   94,
 /*   320 */    90,   94,   94,   61,   86,   87,   88,   89,   90,   67,
 /*   330 */    68,   69,   70,   71,   72,   94,   94,   94,   76,   94,
 /*   340 */    78,   94,   80,   94,   94,   94,   94,   61,   86,   87,
 /*   350 */    88,   89,   90,   67,   94,   69,   70,   71,   72,   94,
 /*   360 */    94,   94,   76,   94,   78,   94,   80,   94,   94,   94,
 /*   370 */    94,   61,   86,   87,   88,   89,   90,   67,   94,   69,
 /*   380 */    70,   71,   72,   94,   94,   94,   76,   94,   78,   12,
 /*   390 */    13,   14,   15,   16,   17,   61,   86,   87,   88,   89,
 /*   400 */    90,   67,   25,   69,   70,   71,   72,   94,   94,   94,
 /*   410 */    76,   94,   94,   94,   94,   94,   94,   94,   94,   94,
 /*   420 */    86,   87,   88,   89,   90,
);
    const YY_SHIFT_USE_DFLT = -13;
    const YY_SHIFT_MAX = 55;
    static public $yy_shift_ofst = array(
 /*     0 */    -1,   47,   47,   95,   95,   95,   95,   95,   95,   95,
 /*    10 */    95,   95,  199,  199,   49,   49,  199,  199,  377,    0,
 /*    20 */   124,   98,   98,  199,  211,  199,  199,  199,  211,  199,
 /*    30 */   199,  211,   96,   38,   38,  -12,  -12,   85,  102,  -12,
 /*    40 */    85,  102,  102,  -12,  102,  188,  199,   55,   13,  106,
 /*    50 */    21,  197,  185,  207,  162,  141,
);
    const YY_REDUCE_USE_DFLT = -61;
    const YY_REDUCE_MAX = 46;
    static public $yy_reduce_ofst = array(
 /*     0 */    94,  118,  142,  190,  166,  238,  214,  262,  286,  310,
 /*    10 */   334,  -60,  206,  134,  -57,  -57,  -30,  230,   86,  113,
 /*    20 */   111,   41,   41,   70,   57,   66,   15,  -29,   58,   67,
 /*    30 */    68,    8,  -49,  149,  149,  -49,  -49,  -10,  158,  -49,
 /*    40 */   -10,  104,  -58,  -49,  136,  117,   87,
);
    static public $yyExpectedTokens = array(
        /* 0 */ array(1, 9, 15, 16, 17, 19, 20, 21, 22, 38, 39, 40, 41, 42, 43, 44, 45, 46, 47, 48, 49, 50, 51, 52, 53, 54, 55, 56, ),
        /* 1 */ array(9, 11, 15, 16, 17, 19, 20, 21, 22, 38, 39, 40, 41, 42, 43, 44, 45, 46, 47, 48, 49, 50, 51, 52, 53, 54, 55, 56, ),
        /* 2 */ array(9, 11, 15, 16, 17, 19, 20, 21, 22, 38, 39, 40, 41, 42, 43, 44, 45, 46, 47, 48, 49, 50, 51, 52, 53, 54, 55, 56, ),
        /* 3 */ array(9, 15, 16, 17, 19, 20, 21, 22, 38, 39, 40, 41, 42, 43, 44, 45, 46, 47, 48, 49, 50, 51, 52, 53, 54, 55, 56, ),
        /* 4 */ array(9, 15, 16, 17, 19, 20, 21, 22, 38, 39, 40, 41, 42, 43, 44, 45, 46, 47, 48, 49, 50, 51, 52, 53, 54, 55, 56, ),
        /* 5 */ array(9, 15, 16, 17, 19, 20, 21, 22, 38, 39, 40, 41, 42, 43, 44, 45, 46, 47, 48, 49, 50, 51, 52, 53, 54, 55, 56, ),
        /* 6 */ array(9, 15, 16, 17, 19, 20, 21, 22, 38, 39, 40, 41, 42, 43, 44, 45, 46, 47, 48, 49, 50, 51, 52, 53, 54, 55, 56, ),
        /* 7 */ array(9, 15, 16, 17, 19, 20, 21, 22, 38, 39, 40, 41, 42, 43, 44, 45, 46, 47, 48, 49, 50, 51, 52, 53, 54, 55, 56, ),
        /* 8 */ array(9, 15, 16, 17, 19, 20, 21, 22, 38, 39, 40, 41, 42, 43, 44, 45, 46, 47, 48, 49, 50, 51, 52, 53, 54, 55, 56, ),
        /* 9 */ array(9, 15, 16, 17, 19, 20, 21, 22, 38, 39, 40, 41, 42, 43, 44, 45, 46, 47, 48, 49, 50, 51, 52, 53, 54, 55, 56, ),
        /* 10 */ array(9, 15, 16, 17, 19, 20, 21, 22, 38, 39, 40, 41, 42, 43, 44, 45, 46, 47, 48, 49, 50, 51, 52, 53, 54, 55, 56, ),
        /* 11 */ array(9, 15, 16, 17, 19, 20, 21, 22, 38, 39, 40, 41, 42, 43, 44, 45, 46, 47, 48, 49, 50, 51, 52, 53, 54, 55, 56, ),
        /* 12 */ array(20, ),
        /* 13 */ array(20, ),
        /* 14 */ array(8, 23, 28, 29, 30, 31, 32, 33, 34, 35, ),
        /* 15 */ array(8, 23, 28, 29, 30, 31, 32, 33, 34, 35, ),
        /* 16 */ array(20, ),
        /* 17 */ array(20, ),
        /* 18 */ array(12, 13, 14, 15, 16, 17, 25, ),
        /* 19 */ array(2, 3, 4, 6, ),
        /* 20 */ array(2, 6, ),
        /* 21 */ array(26, 27, ),
        /* 22 */ array(26, 27, ),
        /* 23 */ array(20, ),
        /* 24 */ array(6, ),
        /* 25 */ array(20, ),
        /* 26 */ array(20, ),
        /* 27 */ array(20, ),
        /* 28 */ array(6, ),
        /* 29 */ array(20, ),
        /* 30 */ array(20, ),
        /* 31 */ array(6, ),
        /* 32 */ array(10, 25, ),
        /* 33 */ array(36, 37, ),
        /* 34 */ array(36, 37, ),
        /* 35 */ array(25, ),
        /* 36 */ array(25, ),
        /* 37 */ array(24, ),
        /* 38 */ array(5, ),
        /* 39 */ array(25, ),
        /* 40 */ array(24, ),
        /* 41 */ array(5, ),
        /* 42 */ array(5, ),
        /* 43 */ array(25, ),
        /* 44 */ array(5, ),
        /* 45 */ array(9, ),
        /* 46 */ array(20, ),
        /* 47 */ array(4, 10, ),
        /* 48 */ array(4, 10, ),
        /* 49 */ array(2, 7, ),
        /* 50 */ array(3, 4, ),
        /* 51 */ array(18, ),
        /* 52 */ array(8, ),
        /* 53 */ array(9, ),
        /* 54 */ array(7, ),
        /* 55 */ array(18, ),
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
);
    static public $yy_default = array(
 /*     0 */   232,  169,  232,  232,  232,  232,  232,  232,  232,  232,
 /*    10 */   232,  232,  232,  232,  163,  162,  232,  232,  232,  147,
 /*    20 */   147,  161,  160,  232,  146,  232,  232,  232,  147,  232,
 /*    30 */   232,  147,  232,  159,  158,  172,  168,  165,  144,  167,
 /*    40 */   164,  144,  144,  151,  144,  232,  232,  232,  232,  232,
 /*    50 */   232,  184,  232,  232,  232,  232,  205,  171,  207,  196,
 /*    60 */   166,  206,  148,  186,  150,  204,  149,  139,  142,  140,
 /*    70 */   145,  138,  176,  175,  174,  177,  178,  170,  179,  173,
 /*    80 */   198,  188,  187,  185,  183,  182,  189,  190,  213,  214,
 /*    90 */   212,  211,  210,  181,  180,  152,  153,  143,  137,  136,
 /*   100 */   154,  155,  208,  209,  157,  197,  156,  215,  216,  135,
 /*   110 */   199,  191,  231,  230,  192,  193,  201,  202,  200,  195,
 /*   120 */   194,  229,  228,  220,  221,  219,  218,  217,  222,  223,
 /*   130 */   227,  226,  225,  224,  203,
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
    const YYNOCODE = 95;
    const YYSTACKDEPTH = 100;
    const YYNSTATE = 135;
    const YYNRULE = 97;
    const YYERRORSYMBOL = 57;
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
  '$',             'SELECT',        'AS_ALIAS',      'FROM',        
  'COMA',          'WHERE',         'JOIN',          'ON',          
  'EQ',            'PAR_OPEN',      'PAR_CLOSE',     'INTERVAL',    
  'F_SECOND',      'F_MINUTE',      'F_HOUR',        'F_DAY',       
  'F_MONTH',       'F_YEAR',        'DOT',           'VARNAME',     
  'NAME',          'NUMVAL',        'STRVAL',        'NOT_EQ',      
  'LOG_AND',       'LOG_OR',        'MATH_DIV',      'MATH_MULT',   
  'MATH_PLUS',     'MATH_MINUS',    'GT',            'LT',          
  'GE',            'LE',            'LIKE',          'NOT_LIKE',    
  'IN',            'NOT_IN',        'F_IF',          'F_ELT',       
  'F_COALESCE',    'F_CONCAT',      'F_SUBSTR',      'F_TRIM',      
  'F_DATE',        'F_DATE_FORMAT',  'F_CURRENT_DATE',  'F_NOW',       
  'F_TIME',        'F_TO_DAYS',     'F_FROM_DAYS',   'F_DATE_ADD',  
  'F_DATE_SUB',    'F_ROUND',       'F_FLOOR',       'F_INET_ATON', 
  'F_INET_NTOA',   'error',         'result',        'query',       
  'condition',     'class_name',    'join_statement',  'where_statement',
  'class_list',    'join_item',     'join_condition',  'field_id',    
  'expression_prio4',  'expression_basic',  'scalar',        'var_name',    
  'func_name',     'arg_list',      'list_operator',  'list',        
  'expression_prio1',  'operator1',     'expression_prio2',  'operator2',   
  'expression_prio3',  'operator3',     'operator4',     'list_items',  
  'argument',      'interval_unit',  'num_scalar',    'str_scalar',  
  'num_value',     'str_value',     'name',          'num_operator1',
  'num_operator2',  'str_operator',
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
 /*   4 */ "query ::= SELECT class_list FROM class_name join_statement where_statement",
 /*   5 */ "query ::= SELECT class_list FROM class_name AS_ALIAS class_name join_statement where_statement",
 /*   6 */ "class_list ::= class_name",
 /*   7 */ "class_list ::= class_list COMA class_name",
 /*   8 */ "where_statement ::= WHERE condition",
 /*   9 */ "where_statement ::=",
 /*  10 */ "join_statement ::= join_item join_statement",
 /*  11 */ "join_statement ::= join_item",
 /*  12 */ "join_statement ::=",
 /*  13 */ "join_item ::= JOIN class_name AS_ALIAS class_name ON join_condition",
 /*  14 */ "join_item ::= JOIN class_name ON join_condition",
 /*  15 */ "join_condition ::= field_id EQ field_id",
 /*  16 */ "condition ::= expression_prio4",
 /*  17 */ "expression_basic ::= scalar",
 /*  18 */ "expression_basic ::= field_id",
 /*  19 */ "expression_basic ::= var_name",
 /*  20 */ "expression_basic ::= func_name PAR_OPEN arg_list PAR_CLOSE",
 /*  21 */ "expression_basic ::= PAR_OPEN expression_prio4 PAR_CLOSE",
 /*  22 */ "expression_basic ::= expression_basic list_operator list",
 /*  23 */ "expression_prio1 ::= expression_basic",
 /*  24 */ "expression_prio1 ::= expression_prio1 operator1 expression_basic",
 /*  25 */ "expression_prio2 ::= expression_prio1",
 /*  26 */ "expression_prio2 ::= expression_prio2 operator2 expression_prio1",
 /*  27 */ "expression_prio3 ::= expression_prio2",
 /*  28 */ "expression_prio3 ::= expression_prio3 operator3 expression_prio2",
 /*  29 */ "expression_prio4 ::= expression_prio3",
 /*  30 */ "expression_prio4 ::= expression_prio4 operator4 expression_prio3",
 /*  31 */ "list ::= PAR_OPEN list_items PAR_CLOSE",
 /*  32 */ "list_items ::= expression_prio4",
 /*  33 */ "list_items ::= list_items COMA expression_prio4",
 /*  34 */ "arg_list ::=",
 /*  35 */ "arg_list ::= argument",
 /*  36 */ "arg_list ::= arg_list COMA argument",
 /*  37 */ "argument ::= expression_prio4",
 /*  38 */ "argument ::= INTERVAL expression_prio4 interval_unit",
 /*  39 */ "interval_unit ::= F_SECOND",
 /*  40 */ "interval_unit ::= F_MINUTE",
 /*  41 */ "interval_unit ::= F_HOUR",
 /*  42 */ "interval_unit ::= F_DAY",
 /*  43 */ "interval_unit ::= F_MONTH",
 /*  44 */ "interval_unit ::= F_YEAR",
 /*  45 */ "scalar ::= num_scalar",
 /*  46 */ "scalar ::= str_scalar",
 /*  47 */ "num_scalar ::= num_value",
 /*  48 */ "str_scalar ::= str_value",
 /*  49 */ "field_id ::= name",
 /*  50 */ "field_id ::= class_name DOT name",
 /*  51 */ "class_name ::= name",
 /*  52 */ "var_name ::= VARNAME",
 /*  53 */ "name ::= NAME",
 /*  54 */ "num_value ::= NUMVAL",
 /*  55 */ "str_value ::= STRVAL",
 /*  56 */ "operator1 ::= num_operator1",
 /*  57 */ "operator2 ::= num_operator2",
 /*  58 */ "operator2 ::= str_operator",
 /*  59 */ "operator2 ::= EQ",
 /*  60 */ "operator2 ::= NOT_EQ",
 /*  61 */ "operator3 ::= LOG_AND",
 /*  62 */ "operator4 ::= LOG_OR",
 /*  63 */ "num_operator1 ::= MATH_DIV",
 /*  64 */ "num_operator1 ::= MATH_MULT",
 /*  65 */ "num_operator2 ::= MATH_PLUS",
 /*  66 */ "num_operator2 ::= MATH_MINUS",
 /*  67 */ "num_operator2 ::= GT",
 /*  68 */ "num_operator2 ::= LT",
 /*  69 */ "num_operator2 ::= GE",
 /*  70 */ "num_operator2 ::= LE",
 /*  71 */ "str_operator ::= LIKE",
 /*  72 */ "str_operator ::= NOT_LIKE",
 /*  73 */ "list_operator ::= IN",
 /*  74 */ "list_operator ::= NOT_IN",
 /*  75 */ "func_name ::= F_IF",
 /*  76 */ "func_name ::= F_ELT",
 /*  77 */ "func_name ::= F_COALESCE",
 /*  78 */ "func_name ::= F_CONCAT",
 /*  79 */ "func_name ::= F_SUBSTR",
 /*  80 */ "func_name ::= F_TRIM",
 /*  81 */ "func_name ::= F_DATE",
 /*  82 */ "func_name ::= F_DATE_FORMAT",
 /*  83 */ "func_name ::= F_CURRENT_DATE",
 /*  84 */ "func_name ::= F_NOW",
 /*  85 */ "func_name ::= F_TIME",
 /*  86 */ "func_name ::= F_TO_DAYS",
 /*  87 */ "func_name ::= F_FROM_DAYS",
 /*  88 */ "func_name ::= F_YEAR",
 /*  89 */ "func_name ::= F_MONTH",
 /*  90 */ "func_name ::= F_DAY",
 /*  91 */ "func_name ::= F_DATE_ADD",
 /*  92 */ "func_name ::= F_DATE_SUB",
 /*  93 */ "func_name ::= F_ROUND",
 /*  94 */ "func_name ::= F_FLOOR",
 /*  95 */ "func_name ::= F_INET_ATON",
 /*  96 */ "func_name ::= F_INET_NTOA",
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
  array( 'lhs' => 58, 'rhs' => 1 ),
  array( 'lhs' => 58, 'rhs' => 1 ),
  array( 'lhs' => 59, 'rhs' => 4 ),
  array( 'lhs' => 59, 'rhs' => 6 ),
  array( 'lhs' => 59, 'rhs' => 6 ),
  array( 'lhs' => 59, 'rhs' => 8 ),
  array( 'lhs' => 64, 'rhs' => 1 ),
  array( 'lhs' => 64, 'rhs' => 3 ),
  array( 'lhs' => 63, 'rhs' => 2 ),
  array( 'lhs' => 63, 'rhs' => 0 ),
  array( 'lhs' => 62, 'rhs' => 2 ),
  array( 'lhs' => 62, 'rhs' => 1 ),
  array( 'lhs' => 62, 'rhs' => 0 ),
  array( 'lhs' => 65, 'rhs' => 6 ),
  array( 'lhs' => 65, 'rhs' => 4 ),
  array( 'lhs' => 66, 'rhs' => 3 ),
  array( 'lhs' => 60, 'rhs' => 1 ),
  array( 'lhs' => 69, 'rhs' => 1 ),
  array( 'lhs' => 69, 'rhs' => 1 ),
  array( 'lhs' => 69, 'rhs' => 1 ),
  array( 'lhs' => 69, 'rhs' => 4 ),
  array( 'lhs' => 69, 'rhs' => 3 ),
  array( 'lhs' => 69, 'rhs' => 3 ),
  array( 'lhs' => 76, 'rhs' => 1 ),
  array( 'lhs' => 76, 'rhs' => 3 ),
  array( 'lhs' => 78, 'rhs' => 1 ),
  array( 'lhs' => 78, 'rhs' => 3 ),
  array( 'lhs' => 80, 'rhs' => 1 ),
  array( 'lhs' => 80, 'rhs' => 3 ),
  array( 'lhs' => 68, 'rhs' => 1 ),
  array( 'lhs' => 68, 'rhs' => 3 ),
  array( 'lhs' => 75, 'rhs' => 3 ),
  array( 'lhs' => 83, 'rhs' => 1 ),
  array( 'lhs' => 83, 'rhs' => 3 ),
  array( 'lhs' => 73, 'rhs' => 0 ),
  array( 'lhs' => 73, 'rhs' => 1 ),
  array( 'lhs' => 73, 'rhs' => 3 ),
  array( 'lhs' => 84, 'rhs' => 1 ),
  array( 'lhs' => 84, 'rhs' => 3 ),
  array( 'lhs' => 85, 'rhs' => 1 ),
  array( 'lhs' => 85, 'rhs' => 1 ),
  array( 'lhs' => 85, 'rhs' => 1 ),
  array( 'lhs' => 85, 'rhs' => 1 ),
  array( 'lhs' => 85, 'rhs' => 1 ),
  array( 'lhs' => 85, 'rhs' => 1 ),
  array( 'lhs' => 70, 'rhs' => 1 ),
  array( 'lhs' => 70, 'rhs' => 1 ),
  array( 'lhs' => 86, 'rhs' => 1 ),
  array( 'lhs' => 87, 'rhs' => 1 ),
  array( 'lhs' => 67, 'rhs' => 1 ),
  array( 'lhs' => 67, 'rhs' => 3 ),
  array( 'lhs' => 61, 'rhs' => 1 ),
  array( 'lhs' => 71, 'rhs' => 1 ),
  array( 'lhs' => 90, 'rhs' => 1 ),
  array( 'lhs' => 88, 'rhs' => 1 ),
  array( 'lhs' => 89, 'rhs' => 1 ),
  array( 'lhs' => 77, 'rhs' => 1 ),
  array( 'lhs' => 79, 'rhs' => 1 ),
  array( 'lhs' => 79, 'rhs' => 1 ),
  array( 'lhs' => 79, 'rhs' => 1 ),
  array( 'lhs' => 79, 'rhs' => 1 ),
  array( 'lhs' => 81, 'rhs' => 1 ),
  array( 'lhs' => 82, 'rhs' => 1 ),
  array( 'lhs' => 91, 'rhs' => 1 ),
  array( 'lhs' => 91, 'rhs' => 1 ),
  array( 'lhs' => 92, 'rhs' => 1 ),
  array( 'lhs' => 92, 'rhs' => 1 ),
  array( 'lhs' => 92, 'rhs' => 1 ),
  array( 'lhs' => 92, 'rhs' => 1 ),
  array( 'lhs' => 92, 'rhs' => 1 ),
  array( 'lhs' => 92, 'rhs' => 1 ),
  array( 'lhs' => 93, 'rhs' => 1 ),
  array( 'lhs' => 93, 'rhs' => 1 ),
  array( 'lhs' => 74, 'rhs' => 1 ),
  array( 'lhs' => 74, 'rhs' => 1 ),
  array( 'lhs' => 72, 'rhs' => 1 ),
  array( 'lhs' => 72, 'rhs' => 1 ),
  array( 'lhs' => 72, 'rhs' => 1 ),
  array( 'lhs' => 72, 'rhs' => 1 ),
  array( 'lhs' => 72, 'rhs' => 1 ),
  array( 'lhs' => 72, 'rhs' => 1 ),
  array( 'lhs' => 72, 'rhs' => 1 ),
  array( 'lhs' => 72, 'rhs' => 1 ),
  array( 'lhs' => 72, 'rhs' => 1 ),
  array( 'lhs' => 72, 'rhs' => 1 ),
  array( 'lhs' => 72, 'rhs' => 1 ),
  array( 'lhs' => 72, 'rhs' => 1 ),
  array( 'lhs' => 72, 'rhs' => 1 ),
  array( 'lhs' => 72, 'rhs' => 1 ),
  array( 'lhs' => 72, 'rhs' => 1 ),
  array( 'lhs' => 72, 'rhs' => 1 ),
  array( 'lhs' => 72, 'rhs' => 1 ),
  array( 'lhs' => 72, 'rhs' => 1 ),
  array( 'lhs' => 72, 'rhs' => 1 ),
  array( 'lhs' => 72, 'rhs' => 1 ),
  array( 'lhs' => 72, 'rhs' => 1 ),
  array( 'lhs' => 72, 'rhs' => 1 ),
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
        6 => 6,
        32 => 6,
        35 => 6,
        7 => 7,
        33 => 7,
        36 => 7,
        8 => 8,
        9 => 9,
        12 => 9,
        10 => 10,
        11 => 11,
        13 => 13,
        14 => 14,
        15 => 15,
        16 => 16,
        17 => 16,
        18 => 16,
        19 => 16,
        23 => 16,
        25 => 16,
        27 => 16,
        29 => 16,
        37 => 16,
        39 => 16,
        40 => 16,
        41 => 16,
        42 => 16,
        43 => 16,
        44 => 16,
        45 => 16,
        46 => 16,
        20 => 20,
        21 => 21,
        22 => 22,
        24 => 22,
        26 => 22,
        28 => 22,
        30 => 22,
        31 => 31,
        34 => 34,
        38 => 38,
        47 => 47,
        48 => 47,
        49 => 49,
        50 => 50,
        51 => 51,
        75 => 51,
        76 => 51,
        77 => 51,
        78 => 51,
        79 => 51,
        80 => 51,
        81 => 51,
        82 => 51,
        83 => 51,
        84 => 51,
        85 => 51,
        86 => 51,
        87 => 51,
        88 => 51,
        89 => 51,
        90 => 51,
        91 => 51,
        92 => 51,
        93 => 51,
        94 => 51,
        95 => 51,
        96 => 51,
        52 => 52,
        53 => 53,
        54 => 54,
        56 => 54,
        57 => 54,
        58 => 54,
        59 => 54,
        60 => 54,
        61 => 54,
        62 => 54,
        63 => 54,
        64 => 54,
        65 => 54,
        66 => 54,
        67 => 54,
        68 => 54,
        69 => 54,
        70 => 54,
        71 => 54,
        72 => 54,
        73 => 54,
        74 => 54,
        55 => 55,
    );
    /* Beginning here are the reduction cases.  A typical example
    ** follows:
    **  #line <lineno> <grammarfile>
    **   function yy_r0($yymsp){ ... }           // User supplied code
    **  #line <lineno> <thisfile>
    */
#line 29 "oql-parser.y"
    function yy_r0(){ $this->my_result = $this->yystack[$this->yyidx + 0]->minor;     }
#line 1296 "oql-parser.php"
#line 32 "oql-parser.y"
    function yy_r2(){
	$this->_retvalue = new OqlObjectQuery($this->yystack[$this->yyidx + -2]->minor, $this->yystack[$this->yyidx + -2]->minor, $this->yystack[$this->yyidx + 0]->minor, $this->yystack[$this->yyidx + -1]->minor, array($this->yystack[$this->yyidx + -2]->minor));
    }
#line 1301 "oql-parser.php"
#line 35 "oql-parser.y"
    function yy_r3(){
	$this->_retvalue = new OqlObjectQuery($this->yystack[$this->yyidx + -4]->minor, $this->yystack[$this->yyidx + -2]->minor, $this->yystack[$this->yyidx + 0]->minor, $this->yystack[$this->yyidx + -1]->minor, array($this->yystack[$this->yyidx + -2]->minor));
    }
#line 1306 "oql-parser.php"
#line 39 "oql-parser.y"
    function yy_r4(){
	$this->_retvalue = new OqlObjectQuery($this->yystack[$this->yyidx + -2]->minor, $this->yystack[$this->yyidx + -2]->minor, $this->yystack[$this->yyidx + 0]->minor, $this->yystack[$this->yyidx + -1]->minor, $this->yystack[$this->yyidx + -4]->minor);
    }
#line 1311 "oql-parser.php"
#line 42 "oql-parser.y"
    function yy_r5(){
	$this->_retvalue = new OqlObjectQuery($this->yystack[$this->yyidx + -4]->minor, $this->yystack[$this->yyidx + -2]->minor, $this->yystack[$this->yyidx + 0]->minor, $this->yystack[$this->yyidx + -1]->minor, $this->yystack[$this->yyidx + -6]->minor);
    }
#line 1316 "oql-parser.php"
#line 47 "oql-parser.y"
    function yy_r6(){
	$this->_retvalue = array($this->yystack[$this->yyidx + 0]->minor);
    }
#line 1321 "oql-parser.php"
#line 50 "oql-parser.y"
    function yy_r7(){
	array_push($this->yystack[$this->yyidx + -2]->minor, $this->yystack[$this->yyidx + 0]->minor);
	$this->_retvalue = $this->yystack[$this->yyidx + -2]->minor;
    }
#line 1327 "oql-parser.php"
#line 55 "oql-parser.y"
    function yy_r8(){ $this->_retvalue = $this->yystack[$this->yyidx + 0]->minor;    }
#line 1330 "oql-parser.php"
#line 56 "oql-parser.y"
    function yy_r9(){ $this->_retvalue = null;    }
#line 1333 "oql-parser.php"
#line 58 "oql-parser.y"
    function yy_r10(){
	// insert the join statement on top of the existing list
	array_unshift($this->yystack[$this->yyidx + 0]->minor, $this->yystack[$this->yyidx + -1]->minor);
	// and return the updated array
	$this->_retvalue = $this->yystack[$this->yyidx + 0]->minor;
    }
#line 1341 "oql-parser.php"
#line 64 "oql-parser.y"
    function yy_r11(){
	$this->_retvalue = Array($this->yystack[$this->yyidx + 0]->minor);
    }
#line 1346 "oql-parser.php"
#line 70 "oql-parser.y"
    function yy_r13(){
	// create an array with one single item
	$this->_retvalue = new OqlJoinSpec($this->yystack[$this->yyidx + -4]->minor, $this->yystack[$this->yyidx + -2]->minor, $this->yystack[$this->yyidx + 0]->minor);
    }
#line 1352 "oql-parser.php"
#line 75 "oql-parser.y"
    function yy_r14(){
	// create an array with one single item
	$this->_retvalue = new OqlJoinSpec($this->yystack[$this->yyidx + -2]->minor, $this->yystack[$this->yyidx + -2]->minor, $this->yystack[$this->yyidx + 0]->minor);
    }
#line 1358 "oql-parser.php"
#line 80 "oql-parser.y"
    function yy_r15(){ $this->_retvalue = new BinaryOqlExpression($this->yystack[$this->yyidx + -2]->minor, '=', $this->yystack[$this->yyidx + 0]->minor);     }
#line 1361 "oql-parser.php"
#line 82 "oql-parser.y"
    function yy_r16(){ $this->_retvalue = $this->yystack[$this->yyidx + 0]->minor;     }
#line 1364 "oql-parser.php"
#line 87 "oql-parser.y"
    function yy_r20(){ $this->_retvalue = new FunctionOqlExpression($this->yystack[$this->yyidx + -3]->minor, $this->yystack[$this->yyidx + -1]->minor);     }
#line 1367 "oql-parser.php"
#line 88 "oql-parser.y"
    function yy_r21(){ $this->_retvalue = $this->yystack[$this->yyidx + -1]->minor;     }
#line 1370 "oql-parser.php"
#line 89 "oql-parser.y"
    function yy_r22(){ $this->_retvalue = new BinaryOqlExpression($this->yystack[$this->yyidx + -2]->minor, $this->yystack[$this->yyidx + -1]->minor, $this->yystack[$this->yyidx + 0]->minor);     }
#line 1373 "oql-parser.php"
#line 104 "oql-parser.y"
    function yy_r31(){
	$this->_retvalue = new ListOqlExpression($this->yystack[$this->yyidx + -1]->minor);
    }
#line 1378 "oql-parser.php"
#line 115 "oql-parser.y"
    function yy_r34(){
	$this->_retvalue = array();
    }
#line 1383 "oql-parser.php"
#line 126 "oql-parser.y"
    function yy_r38(){ $this->_retvalue = new IntervalOqlExpression($this->yystack[$this->yyidx + -1]->minor, $this->yystack[$this->yyidx + 0]->minor);     }
#line 1386 "oql-parser.php"
#line 138 "oql-parser.y"
    function yy_r47(){ $this->_retvalue = new ScalarOqlExpression($this->yystack[$this->yyidx + 0]->minor);     }
#line 1389 "oql-parser.php"
#line 141 "oql-parser.y"
    function yy_r49(){ $this->_retvalue = new FieldOqlExpression($this->yystack[$this->yyidx + 0]->minor);     }
#line 1392 "oql-parser.php"
#line 142 "oql-parser.y"
    function yy_r50(){ $this->_retvalue = new FieldOqlExpression($this->yystack[$this->yyidx + 0]->minor, $this->yystack[$this->yyidx + -2]->minor);     }
#line 1395 "oql-parser.php"
#line 143 "oql-parser.y"
    function yy_r51(){ $this->_retvalue=$this->yystack[$this->yyidx + 0]->minor;     }
#line 1398 "oql-parser.php"
#line 146 "oql-parser.y"
    function yy_r52(){ $this->_retvalue = new VariableOqlExpression(substr($this->yystack[$this->yyidx + 0]->minor, 1));     }
#line 1401 "oql-parser.php"
#line 148 "oql-parser.y"
    function yy_r53(){
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
#line 1414 "oql-parser.php"
#line 160 "oql-parser.y"
    function yy_r54(){$this->_retvalue=$this->yystack[$this->yyidx + 0]->minor;    }
#line 1417 "oql-parser.php"
#line 161 "oql-parser.y"
    function yy_r55(){$this->_retvalue=stripslashes(substr($this->yystack[$this->yyidx + 0]->minor, 1, strlen($this->yystack[$this->yyidx + 0]->minor) - 2));    }
#line 1420 "oql-parser.php"

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
#line 25 "oql-parser.y"
 
throw new OQLParserException($this->m_sSourceQuery, $this->m_iLine, $this->m_iCol, $this->tokenName($yymajor), $TOKEN);
#line 1536 "oql-parser.php"
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
}#line 211 "oql-parser.y"


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

#line 1755 "oql-parser.php"

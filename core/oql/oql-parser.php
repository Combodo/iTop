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
    const BELOW                          =  9;
    const BELOW_STRICT                   = 10;
    const NOT_BELOW                      = 11;
    const NOT_BELOW_STRICT               = 12;
    const ABOVE                          = 13;
    const ABOVE_STRICT                   = 14;
    const NOT_ABOVE                      = 15;
    const NOT_ABOVE_STRICT               = 16;
    const PAR_OPEN                       = 17;
    const PAR_CLOSE                      = 18;
    const INTERVAL                       = 19;
    const F_SECOND                       = 20;
    const F_MINUTE                       = 21;
    const F_HOUR                         = 22;
    const F_DAY                          = 23;
    const F_MONTH                        = 24;
    const F_YEAR                         = 25;
    const DOT                            = 26;
    const VARNAME                        = 27;
    const NAME                           = 28;
    const NUMVAL                         = 29;
    const STRVAL                         = 30;
    const REGEXP                         = 31;
    const NOT_EQ                         = 32;
    const LOG_AND                        = 33;
    const LOG_OR                         = 34;
    const MATH_DIV                       = 35;
    const MATH_MULT                      = 36;
    const MATH_PLUS                      = 37;
    const MATH_MINUS                     = 38;
    const GT                             = 39;
    const LT                             = 40;
    const GE                             = 41;
    const LE                             = 42;
    const LIKE                           = 43;
    const NOT_LIKE                       = 44;
    const IN                             = 45;
    const NOT_IN                         = 46;
    const F_IF                           = 47;
    const F_ELT                          = 48;
    const F_COALESCE                     = 49;
    const F_ISNULL                       = 50;
    const F_CONCAT                       = 51;
    const F_SUBSTR                       = 52;
    const F_TRIM                         = 53;
    const F_DATE                         = 54;
    const F_DATE_FORMAT                  = 55;
    const F_CURRENT_DATE                 = 56;
    const F_NOW                          = 57;
    const F_TIME                         = 58;
    const F_TO_DAYS                      = 59;
    const F_FROM_DAYS                    = 60;
    const F_DATE_ADD                     = 61;
    const F_DATE_SUB                     = 62;
    const F_ROUND                        = 63;
    const F_FLOOR                        = 64;
    const F_INET_ATON                    = 65;
    const F_INET_NTOA                    = 66;
    const YY_NO_ACTION = 262;
    const YY_ACCEPT_ACTION = 261;
    const YY_ERROR_ACTION = 260;

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
    const YY_SZ_ACTTAB = 486;
static public $yy_action = array(
 /*     0 */    16,   71,   69,   68,   70,   65,   66,   10,   91,   31,
 /*    10 */   159,  159,   80,   35,  115,  120,    6,  137,  127,   11,
 /*    20 */   128,  126,  145,  140,  139,    8,   94,   95,   92,   93,
 /*    30 */   120,  125,  124,  138,  118,  116,   13,  129,  130,  135,
 /*    40 */   136,  134,  133,  131,  132,   53,   98,   99,  104,  105,
 /*    50 */   103,  102,  100,  101,  121,  122,  143,  144,  142,  141,
 /*    60 */   146,  151,  150,  149,  147,  148,    6,   48,    7,    9,
 /*    70 */    33,   34,  145,  140,  139,   35,   94,   95,   92,   93,
 /*    80 */     5,   25,   20,   19,   18,   21,   24,   22,   23,   17,
 /*    90 */    84,   36,   27,   33,   64,   56,   98,   99,  104,  105,
 /*   100 */   103,  102,  100,  101,  121,  122,  143,  144,  142,  141,
 /*   110 */   146,  151,  150,  149,  147,  148,    6,   37,   39,   78,
 /*   120 */    74,   74,  145,  140,  139,  120,   94,   95,   92,   93,
 /*   130 */    44,    8,   38,   33,   67,   50,   46,   12,   33,   33,
 /*   140 */     3,    1,   79,  212,  152,   28,   98,   99,  104,  105,
 /*   150 */   103,  102,  100,  101,  121,  122,  143,  144,  142,  141,
 /*   160 */   146,  151,  150,  149,  147,  148,  261,  123,  112,   62,
 /*   170 */    95,   74,   58,   61,   74,  108,   47,   40,  110,  109,
 /*   180 */    63,   97,  119,   62,   30,   35,   15,    4,   45,   81,
 /*   190 */    49,  113,   32,   85,  117,  107,  106,   96,   60,   62,
 /*   200 */   213,   74,   74,    2,  213,  108,   52,   40,  110,  109,
 /*   210 */    63,   57,   60,  213,   30,  213,   15,  114,   45,  213,
 /*   220 */   213,   74,   90,   62,  117,  107,  106,   96,   60,  108,
 /*   230 */    52,   40,  110,  109,   63,  213,  213,  213,   30,   62,
 /*   240 */    15,  213,   45,  213,   77,   55,   72,   62,  117,  107,
 /*   250 */   106,   96,   60,  108,   43,   40,  110,  109,   63,  213,
 /*   260 */   213,  213,   30,  213,   15,  213,   45,  213,   60,   59,
 /*   270 */   111,   62,  117,  107,  106,   96,   60,  108,   47,   40,
 /*   280 */   110,  109,   63,  213,  213,  213,   30,  213,   15,  213,
 /*   290 */    45,  213,  213,  213,  213,   62,  117,  107,  106,   96,
 /*   300 */    60,  108,   51,   40,  110,  109,   63,  213,  213,  213,
 /*   310 */    30,   62,   15,  213,   45,  213,   83,   55,  213,   62,
 /*   320 */   117,  107,  106,   96,   60,  108,   42,   40,  110,  109,
 /*   330 */    63,  213,  213,  213,   30,  213,   15,  213,   45,  213,
 /*   340 */    60,  213,  213,   62,  117,  107,  106,   96,   60,  108,
 /*   350 */    26,   40,  110,  109,   63,  213,  213,  213,   30,  213,
 /*   360 */    15,  213,   45,  213,  213,  213,  213,   62,  117,  107,
 /*   370 */   106,   96,   60,  108,   62,   40,  110,  109,   63,  213,
 /*   380 */    73,  213,   30,   62,   15,  213,   54,  213,  213,   82,
 /*   390 */   213,   62,  117,  107,  106,   96,   60,  108,   62,   40,
 /*   400 */   110,  109,   63,   60,   86,  213,   30,  213,   14,  213,
 /*   410 */   213,  213,   60,  213,  213,   62,  117,  107,  106,   96,
 /*   420 */    60,  108,   62,   40,  110,  109,   63,   60,   89,  213,
 /*   430 */    29,  213,   62,  213,  213,  213,  213,  213,   76,   62,
 /*   440 */   117,  107,  106,   96,   60,  108,   62,   41,  110,  109,
 /*   450 */    63,   60,   87,  213,   62,  213,   62,  213,  213,  213,
 /*   460 */    75,   60,   88,  213,  117,  107,  106,   96,   60,  213,
 /*   470 */   213,  213,  213,  213,  213,   60,  213,  213,  213,  213,
 /*   480 */   213,  213,  213,   60,  213,   60,
    );
    static public $yy_lookahead = array(
 /*     0 */     1,   20,   21,   22,   23,   24,   25,   89,    8,    2,
 /*    10 */     3,    4,   73,    6,   18,   34,   17,   35,   36,   87,
 /*    20 */   102,  103,   23,   24,   25,   92,   27,   28,   29,   30,
 /*    30 */    34,   31,   32,  101,   45,   46,    7,   37,   38,   39,
 /*    40 */    40,   41,   42,   43,   44,   26,   47,   48,   49,   50,
 /*    50 */    51,   52,   53,   54,   55,   56,   57,   58,   59,   60,
 /*    60 */    61,   62,   63,   64,   65,   66,   17,   72,   19,   91,
 /*    70 */    75,    2,   23,   24,   25,    6,   27,   28,   29,   30,
 /*    80 */     4,    8,    9,   10,   11,   12,   13,   14,   15,   16,
 /*    90 */    72,   71,   71,   75,   18,   74,   47,   48,   49,   50,
 /*   100 */    51,   52,   53,   54,   55,   56,   57,   58,   59,   60,
 /*   110 */    61,   62,   63,   64,   65,   66,   17,    3,    4,   73,
 /*   120 */   100,  100,   23,   24,   25,   34,   27,   28,   29,   30,
 /*   130 */    72,   92,    2,   75,   95,   72,   72,    7,   75,   75,
 /*   140 */     5,   17,   71,   26,   33,   71,   47,   48,   49,   50,
 /*   150 */    51,   52,   53,   54,   55,   56,   57,   58,   59,   60,
 /*   160 */    61,   62,   63,   64,   65,   66,   68,   69,   70,   71,
 /*   170 */    28,  100,   71,   71,  100,   77,   78,   79,   80,   81,
 /*   180 */    82,  100,   85,   71,   86,    6,   88,   17,   90,   77,
 /*   190 */    84,   73,   71,   73,   96,   97,   98,   99,  100,   71,
 /*   200 */   104,  100,  100,    4,  104,   77,   78,   79,   80,   81,
 /*   210 */    82,   83,  100,  104,   86,  104,   88,   18,   90,  104,
 /*   220 */   104,  100,   94,   71,   96,   97,   98,   99,  100,   77,
 /*   230 */    78,   79,   80,   81,   82,  104,  104,  104,   86,   71,
 /*   240 */    88,  104,   90,  104,   76,   77,   94,   71,   96,   97,
 /*   250 */    98,   99,  100,   77,   78,   79,   80,   81,   82,  104,
 /*   260 */   104,  104,   86,  104,   88,  104,   90,  104,  100,   93,
 /*   270 */    70,   71,   96,   97,   98,   99,  100,   77,   78,   79,
 /*   280 */    80,   81,   82,  104,  104,  104,   86,  104,   88,  104,
 /*   290 */    90,  104,  104,  104,  104,   71,   96,   97,   98,   99,
 /*   300 */   100,   77,   78,   79,   80,   81,   82,  104,  104,  104,
 /*   310 */    86,   71,   88,  104,   90,  104,   76,   77,  104,   71,
 /*   320 */    96,   97,   98,   99,  100,   77,   78,   79,   80,   81,
 /*   330 */    82,  104,  104,  104,   86,  104,   88,  104,   90,  104,
 /*   340 */   100,  104,  104,   71,   96,   97,   98,   99,  100,   77,
 /*   350 */    78,   79,   80,   81,   82,  104,  104,  104,   86,  104,
 /*   360 */    88,  104,   90,  104,  104,  104,  104,   71,   96,   97,
 /*   370 */    98,   99,  100,   77,   71,   79,   80,   81,   82,  104,
 /*   380 */    77,  104,   86,   71,   88,  104,   90,  104,  104,   77,
 /*   390 */   104,   71,   96,   97,   98,   99,  100,   77,   71,   79,
 /*   400 */    80,   81,   82,  100,   77,  104,   86,  104,   88,  104,
 /*   410 */   104,  104,  100,  104,  104,   71,   96,   97,   98,   99,
 /*   420 */   100,   77,   71,   79,   80,   81,   82,  100,   77,  104,
 /*   430 */    86,  104,   71,  104,  104,  104,  104,  104,   77,   71,
 /*   440 */    96,   97,   98,   99,  100,   77,   71,   79,   80,   81,
 /*   450 */    82,  100,   77,  104,   71,  104,   71,  104,  104,  104,
 /*   460 */    77,  100,   77,  104,   96,   97,   98,   99,  100,  104,
 /*   470 */   104,  104,  104,  104,  104,  100,  104,  104,  104,  104,
 /*   480 */   104,  104,  104,  100,  104,  100,
);
    const YY_SHIFT_USE_DFLT = -20;
    const YY_SHIFT_MAX = 63;
    static public $yy_shift_ofst = array(
 /*     0 */    -1,   49,   49,   99,   99,   99,   99,   99,   99,   99,
 /*    10 */    99,   99,  142,  142,    0,    0,  142,  142,  142,  142,
 /*    20 */   142,  142,  142,  142,  142,  142,  -19,    7,   69,  -18,
 /*    30 */   -18,  142,  179,  179,  142,  142,  179,  142,  142,  142,
 /*    40 */   -11,  -11,   -4,   91,  135,  111,  135,   91,  135,  170,
 /*    50 */   135,   91,   91,  142,  111,   73,  114,  199,  130,   76,
 /*    60 */   117,   29,   19,  124,
);
    const YY_REDUCE_USE_DFLT = -83;
    const YY_REDUCE_MAX = 54;
    static public $yy_reduce_ofst = array(
 /*     0 */    98,  128,  152,  200,  176,  224,  248,  272,  296,  320,
 /*    10 */   344,  368,  168,  240,  -82,  -82,   21,  383,  385,  351,
 /*    20 */   375,  303,  112,  361,  312,  327,   39,   63,   64,  -68,
 /*    30 */   -68,   20,   -5,   18,  121,  101,   58,   74,  102,   71,
 /*    40 */   106,  106,  -67,  -67,  120,  -22,   46,  -67,  -61,   97,
 /*    50 */   118,  -67,  -67,   81,  -22,
);
    static public $yyExpectedTokens = array(
        /* 0 */ array(1, 17, 23, 24, 25, 27, 28, 29, 30, 47, 48, 49, 50, 51, 52, 53, 54, 55, 56, 57, 58, 59, 60, 61, 62, 63, 64, 65, 66, ),
        /* 1 */ array(17, 19, 23, 24, 25, 27, 28, 29, 30, 47, 48, 49, 50, 51, 52, 53, 54, 55, 56, 57, 58, 59, 60, 61, 62, 63, 64, 65, 66, ),
        /* 2 */ array(17, 19, 23, 24, 25, 27, 28, 29, 30, 47, 48, 49, 50, 51, 52, 53, 54, 55, 56, 57, 58, 59, 60, 61, 62, 63, 64, 65, 66, ),
        /* 3 */ array(17, 23, 24, 25, 27, 28, 29, 30, 47, 48, 49, 50, 51, 52, 53, 54, 55, 56, 57, 58, 59, 60, 61, 62, 63, 64, 65, 66, ),
        /* 4 */ array(17, 23, 24, 25, 27, 28, 29, 30, 47, 48, 49, 50, 51, 52, 53, 54, 55, 56, 57, 58, 59, 60, 61, 62, 63, 64, 65, 66, ),
        /* 5 */ array(17, 23, 24, 25, 27, 28, 29, 30, 47, 48, 49, 50, 51, 52, 53, 54, 55, 56, 57, 58, 59, 60, 61, 62, 63, 64, 65, 66, ),
        /* 6 */ array(17, 23, 24, 25, 27, 28, 29, 30, 47, 48, 49, 50, 51, 52, 53, 54, 55, 56, 57, 58, 59, 60, 61, 62, 63, 64, 65, 66, ),
        /* 7 */ array(17, 23, 24, 25, 27, 28, 29, 30, 47, 48, 49, 50, 51, 52, 53, 54, 55, 56, 57, 58, 59, 60, 61, 62, 63, 64, 65, 66, ),
        /* 8 */ array(17, 23, 24, 25, 27, 28, 29, 30, 47, 48, 49, 50, 51, 52, 53, 54, 55, 56, 57, 58, 59, 60, 61, 62, 63, 64, 65, 66, ),
        /* 9 */ array(17, 23, 24, 25, 27, 28, 29, 30, 47, 48, 49, 50, 51, 52, 53, 54, 55, 56, 57, 58, 59, 60, 61, 62, 63, 64, 65, 66, ),
        /* 10 */ array(17, 23, 24, 25, 27, 28, 29, 30, 47, 48, 49, 50, 51, 52, 53, 54, 55, 56, 57, 58, 59, 60, 61, 62, 63, 64, 65, 66, ),
        /* 11 */ array(17, 23, 24, 25, 27, 28, 29, 30, 47, 48, 49, 50, 51, 52, 53, 54, 55, 56, 57, 58, 59, 60, 61, 62, 63, 64, 65, 66, ),
        /* 12 */ array(28, ),
        /* 13 */ array(28, ),
        /* 14 */ array(8, 31, 32, 37, 38, 39, 40, 41, 42, 43, 44, ),
        /* 15 */ array(8, 31, 32, 37, 38, 39, 40, 41, 42, 43, 44, ),
        /* 16 */ array(28, ),
        /* 17 */ array(28, ),
        /* 18 */ array(28, ),
        /* 19 */ array(28, ),
        /* 20 */ array(28, ),
        /* 21 */ array(28, ),
        /* 22 */ array(28, ),
        /* 23 */ array(28, ),
        /* 24 */ array(28, ),
        /* 25 */ array(28, ),
        /* 26 */ array(20, 21, 22, 23, 24, 25, 34, ),
        /* 27 */ array(2, 3, 4, 6, ),
        /* 28 */ array(2, 6, ),
        /* 29 */ array(35, 36, ),
        /* 30 */ array(35, 36, ),
        /* 31 */ array(28, ),
        /* 32 */ array(6, ),
        /* 33 */ array(6, ),
        /* 34 */ array(28, ),
        /* 35 */ array(28, ),
        /* 36 */ array(6, ),
        /* 37 */ array(28, ),
        /* 38 */ array(28, ),
        /* 39 */ array(28, ),
        /* 40 */ array(45, 46, ),
        /* 41 */ array(45, 46, ),
        /* 42 */ array(18, 34, ),
        /* 43 */ array(34, ),
        /* 44 */ array(5, ),
        /* 45 */ array(33, ),
        /* 46 */ array(5, ),
        /* 47 */ array(34, ),
        /* 48 */ array(5, ),
        /* 49 */ array(17, ),
        /* 50 */ array(5, ),
        /* 51 */ array(34, ),
        /* 52 */ array(34, ),
        /* 53 */ array(28, ),
        /* 54 */ array(33, ),
        /* 55 */ array(8, 9, 10, 11, 12, 13, 14, 15, 16, ),
        /* 56 */ array(3, 4, ),
        /* 57 */ array(4, 18, ),
        /* 58 */ array(2, 7, ),
        /* 59 */ array(4, 18, ),
        /* 60 */ array(26, ),
        /* 61 */ array(7, ),
        /* 62 */ array(26, ),
        /* 63 */ array(17, ),
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
);
    static public $yy_default = array(
 /*     0 */   260,  195,  260,  260,  260,  260,  260,  260,  260,  260,
 /*    10 */   260,  260,  260,  260,  189,  188,  260,  260,  260,  260,
 /*    20 */   260,  260,  260,  260,  260,  260,  260,  165,  165,  187,
 /*    30 */   186,  260,  165,  164,  260,  260,  165,  260,  260,  260,
 /*    40 */   184,  185,  260,  193,  162,  190,  162,  177,  162,  260,
 /*    50 */   162,  194,  198,  260,  191,  260,  260,  260,  260,  260,
 /*    60 */   210,  260,  260,  260,  192,  204,  205,  199,  202,  201,
 /*    70 */   203,  200,  197,  172,  212,  176,  175,  167,  157,  160,
 /*    80 */   158,  174,  173,  166,  163,  156,  168,  169,  171,  170,
 /*    90 */   196,  221,  215,  216,  213,  214,  209,  211,  237,  238,
 /*   100 */   243,  244,  242,  241,  239,  240,  208,  207,  179,  180,
 /*   110 */   178,  161,  154,  155,  181,  182,  236,  206,  235,  183,
 /*   120 */   224,  245,  246,  153,  222,  220,  219,  226,  218,  227,
 /*   130 */   228,  233,  234,  232,  231,  229,  230,  225,  217,  251,
 /*   140 */   252,  250,  249,  247,  248,  253,  254,  258,  259,  257,
 /*   150 */   256,  255,  223,
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
    const YYNOCODE = 105;
    const YYSTACKDEPTH = 100;
    const YYNSTATE = 153;
    const YYNRULE = 107;
    const YYERRORSYMBOL = 67;
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
  '$',             'SELECT',        'AS_ALIAS',      'FROM',        
  'COMA',          'WHERE',         'JOIN',          'ON',          
  'EQ',            'BELOW',         'BELOW_STRICT',  'NOT_BELOW',   
  'NOT_BELOW_STRICT',  'ABOVE',         'ABOVE_STRICT',  'NOT_ABOVE',   
  'NOT_ABOVE_STRICT',  'PAR_OPEN',      'PAR_CLOSE',     'INTERVAL',    
  'F_SECOND',      'F_MINUTE',      'F_HOUR',        'F_DAY',       
  'F_MONTH',       'F_YEAR',        'DOT',           'VARNAME',     
  'NAME',          'NUMVAL',        'STRVAL',        'REGEXP',      
  'NOT_EQ',        'LOG_AND',       'LOG_OR',        'MATH_DIV',    
  'MATH_MULT',     'MATH_PLUS',     'MATH_MINUS',    'GT',          
  'LT',            'GE',            'LE',            'LIKE',        
  'NOT_LIKE',      'IN',            'NOT_IN',        'F_IF',        
  'F_ELT',         'F_COALESCE',    'F_ISNULL',      'F_CONCAT',    
  'F_SUBSTR',      'F_TRIM',        'F_DATE',        'F_DATE_FORMAT',
  'F_CURRENT_DATE',  'F_NOW',         'F_TIME',        'F_TO_DAYS',   
  'F_FROM_DAYS',   'F_DATE_ADD',    'F_DATE_SUB',    'F_ROUND',     
  'F_FLOOR',       'F_INET_ATON',   'F_INET_NTOA',   'error',       
  'result',        'query',         'condition',     'class_name',  
  'join_statement',  'where_statement',  'class_list',    'join_item',   
  'join_condition',  'field_id',      'expression_prio4',  'expression_basic',
  'scalar',        'var_name',      'func_name',     'arg_list',    
  'list_operator',  'list',          'expression_prio1',  'operator1',   
  'expression_prio2',  'operator2',     'expression_prio3',  'operator3',   
  'operator4',     'list_items',    'argument',      'interval_unit',
  'num_scalar',    'str_scalar',    'num_value',     'str_value',   
  'name',          'num_operator1',  'num_operator2',  'str_operator',
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
 /*  16 */ "join_condition ::= field_id BELOW field_id",
 /*  17 */ "join_condition ::= field_id BELOW_STRICT field_id",
 /*  18 */ "join_condition ::= field_id NOT_BELOW field_id",
 /*  19 */ "join_condition ::= field_id NOT_BELOW_STRICT field_id",
 /*  20 */ "join_condition ::= field_id ABOVE field_id",
 /*  21 */ "join_condition ::= field_id ABOVE_STRICT field_id",
 /*  22 */ "join_condition ::= field_id NOT_ABOVE field_id",
 /*  23 */ "join_condition ::= field_id NOT_ABOVE_STRICT field_id",
 /*  24 */ "condition ::= expression_prio4",
 /*  25 */ "expression_basic ::= scalar",
 /*  26 */ "expression_basic ::= field_id",
 /*  27 */ "expression_basic ::= var_name",
 /*  28 */ "expression_basic ::= func_name PAR_OPEN arg_list PAR_CLOSE",
 /*  29 */ "expression_basic ::= PAR_OPEN expression_prio4 PAR_CLOSE",
 /*  30 */ "expression_basic ::= expression_basic list_operator list",
 /*  31 */ "expression_prio1 ::= expression_basic",
 /*  32 */ "expression_prio1 ::= expression_prio1 operator1 expression_basic",
 /*  33 */ "expression_prio2 ::= expression_prio1",
 /*  34 */ "expression_prio2 ::= expression_prio2 operator2 expression_prio1",
 /*  35 */ "expression_prio3 ::= expression_prio2",
 /*  36 */ "expression_prio3 ::= expression_prio3 operator3 expression_prio2",
 /*  37 */ "expression_prio4 ::= expression_prio3",
 /*  38 */ "expression_prio4 ::= expression_prio4 operator4 expression_prio3",
 /*  39 */ "list ::= PAR_OPEN list_items PAR_CLOSE",
 /*  40 */ "list_items ::= expression_prio4",
 /*  41 */ "list_items ::= list_items COMA expression_prio4",
 /*  42 */ "arg_list ::=",
 /*  43 */ "arg_list ::= argument",
 /*  44 */ "arg_list ::= arg_list COMA argument",
 /*  45 */ "argument ::= expression_prio4",
 /*  46 */ "argument ::= INTERVAL expression_prio4 interval_unit",
 /*  47 */ "interval_unit ::= F_SECOND",
 /*  48 */ "interval_unit ::= F_MINUTE",
 /*  49 */ "interval_unit ::= F_HOUR",
 /*  50 */ "interval_unit ::= F_DAY",
 /*  51 */ "interval_unit ::= F_MONTH",
 /*  52 */ "interval_unit ::= F_YEAR",
 /*  53 */ "scalar ::= num_scalar",
 /*  54 */ "scalar ::= str_scalar",
 /*  55 */ "num_scalar ::= num_value",
 /*  56 */ "str_scalar ::= str_value",
 /*  57 */ "field_id ::= name",
 /*  58 */ "field_id ::= class_name DOT name",
 /*  59 */ "class_name ::= name",
 /*  60 */ "var_name ::= VARNAME",
 /*  61 */ "name ::= NAME",
 /*  62 */ "num_value ::= NUMVAL",
 /*  63 */ "str_value ::= STRVAL",
 /*  64 */ "operator1 ::= num_operator1",
 /*  65 */ "operator2 ::= num_operator2",
 /*  66 */ "operator2 ::= str_operator",
 /*  67 */ "operator2 ::= REGEXP",
 /*  68 */ "operator2 ::= EQ",
 /*  69 */ "operator2 ::= NOT_EQ",
 /*  70 */ "operator3 ::= LOG_AND",
 /*  71 */ "operator4 ::= LOG_OR",
 /*  72 */ "num_operator1 ::= MATH_DIV",
 /*  73 */ "num_operator1 ::= MATH_MULT",
 /*  74 */ "num_operator2 ::= MATH_PLUS",
 /*  75 */ "num_operator2 ::= MATH_MINUS",
 /*  76 */ "num_operator2 ::= GT",
 /*  77 */ "num_operator2 ::= LT",
 /*  78 */ "num_operator2 ::= GE",
 /*  79 */ "num_operator2 ::= LE",
 /*  80 */ "str_operator ::= LIKE",
 /*  81 */ "str_operator ::= NOT_LIKE",
 /*  82 */ "list_operator ::= IN",
 /*  83 */ "list_operator ::= NOT_IN",
 /*  84 */ "func_name ::= F_IF",
 /*  85 */ "func_name ::= F_ELT",
 /*  86 */ "func_name ::= F_COALESCE",
 /*  87 */ "func_name ::= F_ISNULL",
 /*  88 */ "func_name ::= F_CONCAT",
 /*  89 */ "func_name ::= F_SUBSTR",
 /*  90 */ "func_name ::= F_TRIM",
 /*  91 */ "func_name ::= F_DATE",
 /*  92 */ "func_name ::= F_DATE_FORMAT",
 /*  93 */ "func_name ::= F_CURRENT_DATE",
 /*  94 */ "func_name ::= F_NOW",
 /*  95 */ "func_name ::= F_TIME",
 /*  96 */ "func_name ::= F_TO_DAYS",
 /*  97 */ "func_name ::= F_FROM_DAYS",
 /*  98 */ "func_name ::= F_YEAR",
 /*  99 */ "func_name ::= F_MONTH",
 /* 100 */ "func_name ::= F_DAY",
 /* 101 */ "func_name ::= F_DATE_ADD",
 /* 102 */ "func_name ::= F_DATE_SUB",
 /* 103 */ "func_name ::= F_ROUND",
 /* 104 */ "func_name ::= F_FLOOR",
 /* 105 */ "func_name ::= F_INET_ATON",
 /* 106 */ "func_name ::= F_INET_NTOA",
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
  array( 'lhs' => 68, 'rhs' => 1 ),
  array( 'lhs' => 68, 'rhs' => 1 ),
  array( 'lhs' => 69, 'rhs' => 4 ),
  array( 'lhs' => 69, 'rhs' => 6 ),
  array( 'lhs' => 69, 'rhs' => 6 ),
  array( 'lhs' => 69, 'rhs' => 8 ),
  array( 'lhs' => 74, 'rhs' => 1 ),
  array( 'lhs' => 74, 'rhs' => 3 ),
  array( 'lhs' => 73, 'rhs' => 2 ),
  array( 'lhs' => 73, 'rhs' => 0 ),
  array( 'lhs' => 72, 'rhs' => 2 ),
  array( 'lhs' => 72, 'rhs' => 1 ),
  array( 'lhs' => 72, 'rhs' => 0 ),
  array( 'lhs' => 75, 'rhs' => 6 ),
  array( 'lhs' => 75, 'rhs' => 4 ),
  array( 'lhs' => 76, 'rhs' => 3 ),
  array( 'lhs' => 76, 'rhs' => 3 ),
  array( 'lhs' => 76, 'rhs' => 3 ),
  array( 'lhs' => 76, 'rhs' => 3 ),
  array( 'lhs' => 76, 'rhs' => 3 ),
  array( 'lhs' => 76, 'rhs' => 3 ),
  array( 'lhs' => 76, 'rhs' => 3 ),
  array( 'lhs' => 76, 'rhs' => 3 ),
  array( 'lhs' => 76, 'rhs' => 3 ),
  array( 'lhs' => 70, 'rhs' => 1 ),
  array( 'lhs' => 79, 'rhs' => 1 ),
  array( 'lhs' => 79, 'rhs' => 1 ),
  array( 'lhs' => 79, 'rhs' => 1 ),
  array( 'lhs' => 79, 'rhs' => 4 ),
  array( 'lhs' => 79, 'rhs' => 3 ),
  array( 'lhs' => 79, 'rhs' => 3 ),
  array( 'lhs' => 86, 'rhs' => 1 ),
  array( 'lhs' => 86, 'rhs' => 3 ),
  array( 'lhs' => 88, 'rhs' => 1 ),
  array( 'lhs' => 88, 'rhs' => 3 ),
  array( 'lhs' => 90, 'rhs' => 1 ),
  array( 'lhs' => 90, 'rhs' => 3 ),
  array( 'lhs' => 78, 'rhs' => 1 ),
  array( 'lhs' => 78, 'rhs' => 3 ),
  array( 'lhs' => 85, 'rhs' => 3 ),
  array( 'lhs' => 93, 'rhs' => 1 ),
  array( 'lhs' => 93, 'rhs' => 3 ),
  array( 'lhs' => 83, 'rhs' => 0 ),
  array( 'lhs' => 83, 'rhs' => 1 ),
  array( 'lhs' => 83, 'rhs' => 3 ),
  array( 'lhs' => 94, 'rhs' => 1 ),
  array( 'lhs' => 94, 'rhs' => 3 ),
  array( 'lhs' => 95, 'rhs' => 1 ),
  array( 'lhs' => 95, 'rhs' => 1 ),
  array( 'lhs' => 95, 'rhs' => 1 ),
  array( 'lhs' => 95, 'rhs' => 1 ),
  array( 'lhs' => 95, 'rhs' => 1 ),
  array( 'lhs' => 95, 'rhs' => 1 ),
  array( 'lhs' => 80, 'rhs' => 1 ),
  array( 'lhs' => 80, 'rhs' => 1 ),
  array( 'lhs' => 96, 'rhs' => 1 ),
  array( 'lhs' => 97, 'rhs' => 1 ),
  array( 'lhs' => 77, 'rhs' => 1 ),
  array( 'lhs' => 77, 'rhs' => 3 ),
  array( 'lhs' => 71, 'rhs' => 1 ),
  array( 'lhs' => 81, 'rhs' => 1 ),
  array( 'lhs' => 100, 'rhs' => 1 ),
  array( 'lhs' => 98, 'rhs' => 1 ),
  array( 'lhs' => 99, 'rhs' => 1 ),
  array( 'lhs' => 87, 'rhs' => 1 ),
  array( 'lhs' => 89, 'rhs' => 1 ),
  array( 'lhs' => 89, 'rhs' => 1 ),
  array( 'lhs' => 89, 'rhs' => 1 ),
  array( 'lhs' => 89, 'rhs' => 1 ),
  array( 'lhs' => 89, 'rhs' => 1 ),
  array( 'lhs' => 91, 'rhs' => 1 ),
  array( 'lhs' => 92, 'rhs' => 1 ),
  array( 'lhs' => 101, 'rhs' => 1 ),
  array( 'lhs' => 101, 'rhs' => 1 ),
  array( 'lhs' => 102, 'rhs' => 1 ),
  array( 'lhs' => 102, 'rhs' => 1 ),
  array( 'lhs' => 102, 'rhs' => 1 ),
  array( 'lhs' => 102, 'rhs' => 1 ),
  array( 'lhs' => 102, 'rhs' => 1 ),
  array( 'lhs' => 102, 'rhs' => 1 ),
  array( 'lhs' => 103, 'rhs' => 1 ),
  array( 'lhs' => 103, 'rhs' => 1 ),
  array( 'lhs' => 84, 'rhs' => 1 ),
  array( 'lhs' => 84, 'rhs' => 1 ),
  array( 'lhs' => 82, 'rhs' => 1 ),
  array( 'lhs' => 82, 'rhs' => 1 ),
  array( 'lhs' => 82, 'rhs' => 1 ),
  array( 'lhs' => 82, 'rhs' => 1 ),
  array( 'lhs' => 82, 'rhs' => 1 ),
  array( 'lhs' => 82, 'rhs' => 1 ),
  array( 'lhs' => 82, 'rhs' => 1 ),
  array( 'lhs' => 82, 'rhs' => 1 ),
  array( 'lhs' => 82, 'rhs' => 1 ),
  array( 'lhs' => 82, 'rhs' => 1 ),
  array( 'lhs' => 82, 'rhs' => 1 ),
  array( 'lhs' => 82, 'rhs' => 1 ),
  array( 'lhs' => 82, 'rhs' => 1 ),
  array( 'lhs' => 82, 'rhs' => 1 ),
  array( 'lhs' => 82, 'rhs' => 1 ),
  array( 'lhs' => 82, 'rhs' => 1 ),
  array( 'lhs' => 82, 'rhs' => 1 ),
  array( 'lhs' => 82, 'rhs' => 1 ),
  array( 'lhs' => 82, 'rhs' => 1 ),
  array( 'lhs' => 82, 'rhs' => 1 ),
  array( 'lhs' => 82, 'rhs' => 1 ),
  array( 'lhs' => 82, 'rhs' => 1 ),
  array( 'lhs' => 82, 'rhs' => 1 ),
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
        40 => 6,
        43 => 6,
        7 => 7,
        41 => 7,
        44 => 7,
        8 => 8,
        9 => 9,
        12 => 9,
        10 => 10,
        11 => 11,
        13 => 13,
        14 => 14,
        15 => 15,
        16 => 16,
        17 => 17,
        18 => 18,
        19 => 19,
        20 => 20,
        21 => 21,
        22 => 22,
        23 => 23,
        24 => 24,
        25 => 24,
        26 => 24,
        27 => 24,
        31 => 24,
        33 => 24,
        35 => 24,
        37 => 24,
        45 => 24,
        47 => 24,
        48 => 24,
        49 => 24,
        50 => 24,
        51 => 24,
        52 => 24,
        53 => 24,
        54 => 24,
        28 => 28,
        29 => 29,
        30 => 30,
        32 => 30,
        34 => 30,
        36 => 30,
        38 => 30,
        39 => 39,
        42 => 42,
        46 => 46,
        55 => 55,
        56 => 55,
        57 => 57,
        58 => 58,
        59 => 59,
        84 => 59,
        85 => 59,
        86 => 59,
        87 => 59,
        88 => 59,
        89 => 59,
        90 => 59,
        91 => 59,
        92 => 59,
        93 => 59,
        94 => 59,
        95 => 59,
        96 => 59,
        97 => 59,
        98 => 59,
        99 => 59,
        100 => 59,
        101 => 59,
        102 => 59,
        103 => 59,
        104 => 59,
        105 => 59,
        106 => 59,
        60 => 60,
        61 => 61,
        62 => 62,
        64 => 62,
        65 => 62,
        66 => 62,
        67 => 62,
        68 => 62,
        69 => 62,
        70 => 62,
        71 => 62,
        72 => 62,
        73 => 62,
        74 => 62,
        75 => 62,
        76 => 62,
        77 => 62,
        78 => 62,
        79 => 62,
        80 => 62,
        81 => 62,
        82 => 62,
        83 => 62,
        63 => 63,
    );
    /* Beginning here are the reduction cases.  A typical example
    ** follows:
    **  #line <lineno> <grammarfile>
    **   function yy_r0($yymsp){ ... }           // User supplied code
    **  #line <lineno> <thisfile>
    */
#line 29 "oql-parser.y"
    function yy_r0(){ $this->my_result = $this->yystack[$this->yyidx + 0]->minor;     }
#line 1372 "oql-parser.php"
#line 32 "oql-parser.y"
    function yy_r2(){
	$this->_retvalue = new OqlObjectQuery($this->yystack[$this->yyidx + -2]->minor, $this->yystack[$this->yyidx + -2]->minor, $this->yystack[$this->yyidx + 0]->minor, $this->yystack[$this->yyidx + -1]->minor, array($this->yystack[$this->yyidx + -2]->minor));
    }
#line 1377 "oql-parser.php"
#line 35 "oql-parser.y"
    function yy_r3(){
	$this->_retvalue = new OqlObjectQuery($this->yystack[$this->yyidx + -4]->minor, $this->yystack[$this->yyidx + -2]->minor, $this->yystack[$this->yyidx + 0]->minor, $this->yystack[$this->yyidx + -1]->minor, array($this->yystack[$this->yyidx + -2]->minor));
    }
#line 1382 "oql-parser.php"
#line 39 "oql-parser.y"
    function yy_r4(){
	$this->_retvalue = new OqlObjectQuery($this->yystack[$this->yyidx + -2]->minor, $this->yystack[$this->yyidx + -2]->minor, $this->yystack[$this->yyidx + 0]->minor, $this->yystack[$this->yyidx + -1]->minor, $this->yystack[$this->yyidx + -4]->minor);
    }
#line 1387 "oql-parser.php"
#line 42 "oql-parser.y"
    function yy_r5(){
	$this->_retvalue = new OqlObjectQuery($this->yystack[$this->yyidx + -4]->minor, $this->yystack[$this->yyidx + -2]->minor, $this->yystack[$this->yyidx + 0]->minor, $this->yystack[$this->yyidx + -1]->minor, $this->yystack[$this->yyidx + -6]->minor);
    }
#line 1392 "oql-parser.php"
#line 47 "oql-parser.y"
    function yy_r6(){
	$this->_retvalue = array($this->yystack[$this->yyidx + 0]->minor);
    }
#line 1397 "oql-parser.php"
#line 50 "oql-parser.y"
    function yy_r7(){
	array_push($this->yystack[$this->yyidx + -2]->minor, $this->yystack[$this->yyidx + 0]->minor);
	$this->_retvalue = $this->yystack[$this->yyidx + -2]->minor;
    }
#line 1403 "oql-parser.php"
#line 55 "oql-parser.y"
    function yy_r8(){ $this->_retvalue = $this->yystack[$this->yyidx + 0]->minor;    }
#line 1406 "oql-parser.php"
#line 56 "oql-parser.y"
    function yy_r9(){ $this->_retvalue = null;    }
#line 1409 "oql-parser.php"
#line 58 "oql-parser.y"
    function yy_r10(){
	// insert the join statement on top of the existing list
	array_unshift($this->yystack[$this->yyidx + 0]->minor, $this->yystack[$this->yyidx + -1]->minor);
	// and return the updated array
	$this->_retvalue = $this->yystack[$this->yyidx + 0]->minor;
    }
#line 1417 "oql-parser.php"
#line 64 "oql-parser.y"
    function yy_r11(){
	$this->_retvalue = Array($this->yystack[$this->yyidx + 0]->minor);
    }
#line 1422 "oql-parser.php"
#line 70 "oql-parser.y"
    function yy_r13(){
	// create an array with one single item
	$this->_retvalue = new OqlJoinSpec($this->yystack[$this->yyidx + -4]->minor, $this->yystack[$this->yyidx + -2]->minor, $this->yystack[$this->yyidx + 0]->minor);
    }
#line 1428 "oql-parser.php"
#line 75 "oql-parser.y"
    function yy_r14(){
	// create an array with one single item
	$this->_retvalue = new OqlJoinSpec($this->yystack[$this->yyidx + -2]->minor, $this->yystack[$this->yyidx + -2]->minor, $this->yystack[$this->yyidx + 0]->minor);
    }
#line 1434 "oql-parser.php"
#line 80 "oql-parser.y"
    function yy_r15(){ $this->_retvalue = new BinaryOqlExpression($this->yystack[$this->yyidx + -2]->minor, '=', $this->yystack[$this->yyidx + 0]->minor);     }
#line 1437 "oql-parser.php"
#line 81 "oql-parser.y"
    function yy_r16(){ $this->_retvalue = new BinaryOqlExpression($this->yystack[$this->yyidx + -2]->minor, 'BELOW', $this->yystack[$this->yyidx + 0]->minor);     }
#line 1440 "oql-parser.php"
#line 82 "oql-parser.y"
    function yy_r17(){ $this->_retvalue = new BinaryOqlExpression($this->yystack[$this->yyidx + -2]->minor, 'BELOW_STRICT', $this->yystack[$this->yyidx + 0]->minor);     }
#line 1443 "oql-parser.php"
#line 83 "oql-parser.y"
    function yy_r18(){ $this->_retvalue = new BinaryOqlExpression($this->yystack[$this->yyidx + -2]->minor, 'NOT_BELOW', $this->yystack[$this->yyidx + 0]->minor);     }
#line 1446 "oql-parser.php"
#line 84 "oql-parser.y"
    function yy_r19(){ $this->_retvalue = new BinaryOqlExpression($this->yystack[$this->yyidx + -2]->minor, 'NOT_BELOW_STRICT', $this->yystack[$this->yyidx + 0]->minor);     }
#line 1449 "oql-parser.php"
#line 85 "oql-parser.y"
    function yy_r20(){ $this->_retvalue = new BinaryOqlExpression($this->yystack[$this->yyidx + -2]->minor, 'ABOVE', $this->yystack[$this->yyidx + 0]->minor);     }
#line 1452 "oql-parser.php"
#line 86 "oql-parser.y"
    function yy_r21(){ $this->_retvalue = new BinaryOqlExpression($this->yystack[$this->yyidx + -2]->minor, 'ABOVE_STRICT', $this->yystack[$this->yyidx + 0]->minor);     }
#line 1455 "oql-parser.php"
#line 87 "oql-parser.y"
    function yy_r22(){ $this->_retvalue = new BinaryOqlExpression($this->yystack[$this->yyidx + -2]->minor, 'NOT_ABOVE', $this->yystack[$this->yyidx + 0]->minor);     }
#line 1458 "oql-parser.php"
#line 88 "oql-parser.y"
    function yy_r23(){ $this->_retvalue = new BinaryOqlExpression($this->yystack[$this->yyidx + -2]->minor, 'NOT_ABOVE_STRICT', $this->yystack[$this->yyidx + 0]->minor);     }
#line 1461 "oql-parser.php"
#line 90 "oql-parser.y"
    function yy_r24(){ $this->_retvalue = $this->yystack[$this->yyidx + 0]->minor;     }
#line 1464 "oql-parser.php"
#line 95 "oql-parser.y"
    function yy_r28(){ $this->_retvalue = new FunctionOqlExpression($this->yystack[$this->yyidx + -3]->minor, $this->yystack[$this->yyidx + -1]->minor);     }
#line 1467 "oql-parser.php"
#line 96 "oql-parser.y"
    function yy_r29(){ $this->_retvalue = $this->yystack[$this->yyidx + -1]->minor;     }
#line 1470 "oql-parser.php"
#line 97 "oql-parser.y"
    function yy_r30(){ $this->_retvalue = new BinaryOqlExpression($this->yystack[$this->yyidx + -2]->minor, $this->yystack[$this->yyidx + -1]->minor, $this->yystack[$this->yyidx + 0]->minor);     }
#line 1473 "oql-parser.php"
#line 112 "oql-parser.y"
    function yy_r39(){
	$this->_retvalue = new ListOqlExpression($this->yystack[$this->yyidx + -1]->minor);
    }
#line 1478 "oql-parser.php"
#line 123 "oql-parser.y"
    function yy_r42(){
	$this->_retvalue = array();
    }
#line 1483 "oql-parser.php"
#line 134 "oql-parser.y"
    function yy_r46(){ $this->_retvalue = new IntervalOqlExpression($this->yystack[$this->yyidx + -1]->minor, $this->yystack[$this->yyidx + 0]->minor);     }
#line 1486 "oql-parser.php"
#line 146 "oql-parser.y"
    function yy_r55(){ $this->_retvalue = new ScalarOqlExpression($this->yystack[$this->yyidx + 0]->minor);     }
#line 1489 "oql-parser.php"
#line 149 "oql-parser.y"
    function yy_r57(){ $this->_retvalue = new FieldOqlExpression($this->yystack[$this->yyidx + 0]->minor);     }
#line 1492 "oql-parser.php"
#line 150 "oql-parser.y"
    function yy_r58(){ $this->_retvalue = new FieldOqlExpression($this->yystack[$this->yyidx + 0]->minor, $this->yystack[$this->yyidx + -2]->minor);     }
#line 1495 "oql-parser.php"
#line 151 "oql-parser.y"
    function yy_r59(){ $this->_retvalue=$this->yystack[$this->yyidx + 0]->minor;     }
#line 1498 "oql-parser.php"
#line 154 "oql-parser.y"
    function yy_r60(){ $this->_retvalue = new VariableOqlExpression(substr($this->yystack[$this->yyidx + 0]->minor, 1));     }
#line 1501 "oql-parser.php"
#line 156 "oql-parser.y"
    function yy_r61(){
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
#line 1514 "oql-parser.php"
#line 168 "oql-parser.y"
    function yy_r62(){$this->_retvalue=$this->yystack[$this->yyidx + 0]->minor;    }
#line 1517 "oql-parser.php"
#line 169 "oql-parser.y"
    function yy_r63(){$this->_retvalue=stripslashes(substr($this->yystack[$this->yyidx + 0]->minor, 1, strlen($this->yystack[$this->yyidx + 0]->minor) - 2));    }
#line 1520 "oql-parser.php"

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
#line 1636 "oql-parser.php"
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
#line 221 "oql-parser.y"


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

#line 1869 "oql-parser.php"

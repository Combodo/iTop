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
    const MATH_MINUS                     = 30;
    const HEXVAL                         = 31;
    const STRVAL                         = 32;
    const REGEXP                         = 33;
    const NOT_EQ                         = 34;
    const LOG_AND                        = 35;
    const LOG_OR                         = 36;
    const MATH_DIV                       = 37;
    const MATH_MULT                      = 38;
    const MATH_PLUS                      = 39;
    const GT                             = 40;
    const LT                             = 41;
    const GE                             = 42;
    const LE                             = 43;
    const LIKE                           = 44;
    const NOT_LIKE                       = 45;
    const BITWISE_LEFT_SHIFT             = 46;
    const BITWISE_RIGHT_SHIFT            = 47;
    const BITWISE_AND                    = 48;
    const BITWISE_OR                     = 49;
    const BITWISE_XOR                    = 50;
    const IN                             = 51;
    const NOT_IN                         = 52;
    const F_IF                           = 53;
    const F_ELT                          = 54;
    const F_COALESCE                     = 55;
    const F_ISNULL                       = 56;
    const F_CONCAT                       = 57;
    const F_SUBSTR                       = 58;
    const F_TRIM                         = 59;
    const F_DATE                         = 60;
    const F_DATE_FORMAT                  = 61;
    const F_CURRENT_DATE                 = 62;
    const F_NOW                          = 63;
    const F_TIME                         = 64;
    const F_TO_DAYS                      = 65;
    const F_FROM_DAYS                    = 66;
    const F_DATE_ADD                     = 67;
    const F_DATE_SUB                     = 68;
    const F_ROUND                        = 69;
    const F_FLOOR                        = 70;
    const F_INET_ATON                    = 71;
    const F_INET_NTOA                    = 72;
    const YY_NO_ACTION = 283;
    const YY_ACCEPT_ACTION = 282;
    const YY_ERROR_ACTION = 281;

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
    const YY_SZ_ACTTAB = 525;
static public $yy_action = array(
 /*     0 */     6,   41,    7,   71,   28,   39,  155,  150,  149,  122,
 /*    10 */    96,   99,   97,   61,  103,  104,   74,   38,  170,  170,
 /*    20 */     6,   39,  128,  126,   43,   46,  155,  150,  149,  100,
 /*    30 */    96,   99,   97,   61,  103,  104,  110,  111,  109,  108,
 /*    40 */   105,  106,  107,  129,  130,  153,  154,  152,  151,  148,
 /*    50 */   156,  161,  162,  160,  159,  157,  110,  111,  109,  108,
 /*    60 */   105,  106,  107,  129,  130,  153,  154,  152,  151,  148,
 /*    70 */   156,  161,  162,  160,  159,  157,    6,   30,   44,   99,
 /*    80 */    59,   68,  155,  150,  149,   82,   96,   99,   97,   61,
 /*    90 */   103,  104,   21,   22,   23,   24,   27,   25,   26,   20,
 /*   100 */    19,  146,  136,  123,  124,   50,   70,   70,   42,   57,
 /*   110 */   137,  135,  110,  111,  109,  108,  105,  106,  107,  129,
 /*   120 */   130,  153,  154,  152,  151,  148,  156,  161,  162,  160,
 /*   130 */   159,  157,  282,  134,  119,   62,    4,  121,   70,   80,
 /*   140 */   120,  114,   32,   48,  117,  115,   64,   53,   54,   75,
 /*   150 */    18,   42,   15,    8,   37,  122,   89,   13,   36,   98,
 /*   160 */   113,  112,  101,  102,   60,   62,    1,  127,  128,  126,
 /*   170 */    40,  114,   33,   48,  117,  115,   64,   58,   70,   62,
 /*   180 */    18,  223,   15,   11,   37,   76,    9,   70,   86,   39,
 /*   190 */   113,  112,  101,  102,   60,   62,  125,  158,  147,   70,
 /*   200 */    73,  114,   33,   48,  117,  115,   64,   49,   60,    3,
 /*   210 */    18,   62,   15,   62,   37,   10,   88,   55,   67,   81,
 /*   220 */   113,  112,  101,  102,   60,  232,   45,  118,   62,   95,
 /*   230 */   131,   12,    5,  232,  114,   32,   48,  117,  115,   64,
 /*   240 */    60,   52,   60,   18,   42,   15,   77,   37,   83,  232,
 /*   250 */     2,   42,   62,  113,  112,  101,  102,   60,  114,   34,
 /*   260 */    48,  117,  115,   64,  116,   63,   62,   18,  232,   15,
 /*   270 */     8,   37,   79,   51,   56,  232,   42,  113,  112,  101,
 /*   280 */   102,   60,   62,  232,  127,  232,  232,  232,  114,   29,
 /*   290 */    48,  117,  115,   64,   70,   60,  232,   18,   62,   15,
 /*   300 */   232,   37,  232,   72,   55,  232,   62,  113,  112,  101,
 /*   310 */   102,   60,  114,   16,   48,  117,  115,   64,  232,  232,
 /*   320 */   232,   18,  232,   15,  232,   37,  232,   60,  232,  232,
 /*   330 */    62,  113,  112,  101,  102,   60,  114,   31,   48,  117,
 /*   340 */   115,   64,  232,  232,   62,   18,  232,   15,  232,   37,
 /*   350 */    85,  232,  232,  232,  232,  113,  112,  101,  102,   60,
 /*   360 */    62,  232,  232,  232,  232,  232,  114,  232,   48,  117,
 /*   370 */   115,   64,  232,   60,  232,   18,   62,   15,  232,   35,
 /*   380 */   232,  232,   66,  232,   62,  113,  112,  101,  102,   60,
 /*   390 */   114,  232,   48,  117,  115,   64,  232,  232,  232,   18,
 /*   400 */   232,   14,  232,  232,  232,   60,  232,  232,   62,  113,
 /*   410 */   112,  101,  102,   60,  114,   62,   48,  117,  115,   64,
 /*   420 */   133,   94,  232,   17,  232,  232,  232,  232,  232,  232,
 /*   430 */   232,  232,  232,  113,  112,  101,  102,   60,  232,  232,
 /*   440 */   232,  232,  144,  232,   60,  132,  138,  232,  232,  232,
 /*   450 */   232,  139,  145,  143,  142,  140,  141,  163,  232,  232,
 /*   460 */   232,  232,   62,  232,  232,  232,  232,  232,  114,  232,
 /*   470 */    47,  117,  115,   64,  232,  232,   78,   90,   91,   93,
 /*   480 */    92,   87,  232,  232,  232,  232,  232,  113,  112,  101,
 /*   490 */   102,   60,  122,   62,   62,   62,  232,  232,  232,   69,
 /*   500 */    65,   84,  232,  232,  232,  128,  126,  232,  232,  232,
 /*   510 */   232,  232,  232,  232,  232,  232,  232,  232,  232,  232,
 /*   520 */   232,  232,   60,   60,   60,
    );
    static public $yy_lookahead = array(
 /*     0 */    17,    2,   19,   35,    1,    6,   23,   24,   25,   36,
 /*    10 */    27,   28,   29,   30,   31,   32,   48,    2,    3,    4,
 /*    20 */    17,    6,   49,   50,    3,    4,   23,   24,   25,  106,
 /*    30 */    27,   28,   29,   30,   31,   32,   53,   54,   55,   56,
 /*    40 */    57,   58,   59,   60,   61,   62,   63,   64,   65,   66,
 /*    50 */    67,   68,   69,   70,   71,   72,   53,   54,   55,   56,
 /*    60 */    57,   58,   59,   60,   61,   62,   63,   64,   65,   66,
 /*    70 */    67,   68,   69,   70,   71,   72,   17,   77,   77,   28,
 /*    80 */    80,   79,   23,   24,   25,   79,   27,   28,   29,   30,
 /*    90 */    31,   32,    8,    9,   10,   11,   12,   13,   14,   15,
 /*   100 */    16,   37,   38,   51,   52,   78,  106,  106,   81,   77,
 /*   110 */    46,   47,   53,   54,   55,   56,   57,   58,   59,   60,
 /*   120 */    61,   62,   63,   64,   65,   66,   67,   68,   69,   70,
 /*   130 */    71,   72,   74,   75,   76,   77,    5,   18,  106,   79,
 /*   140 */    79,   83,   84,   85,   86,   87,   88,   26,   78,   77,
 /*   150 */    92,   81,   94,   98,   96,   36,  101,    7,   77,   29,
 /*   160 */   102,  103,  104,  105,  106,   77,   17,  112,   49,   50,
 /*   170 */    77,   83,   84,   85,   86,   87,   88,   89,  106,   77,
 /*   180 */    92,   26,   94,   93,   96,   83,   97,  106,  100,    6,
 /*   190 */   102,  103,  104,  105,  106,   77,   91,  107,  108,  106,
 /*   200 */   111,   83,   84,   85,   86,   87,   88,   90,  106,   17,
 /*   210 */    92,   77,   94,   77,   96,   95,   82,   83,  100,   83,
 /*   220 */   102,  103,  104,  105,  106,  113,    2,   76,   77,  109,
 /*   230 */   110,    7,    4,  113,   83,   84,   85,   86,   87,   88,
 /*   240 */   106,   78,  106,   92,   81,   94,   18,   96,   78,  113,
 /*   250 */     4,   81,   77,  102,  103,  104,  105,  106,   83,   84,
 /*   260 */    85,   86,   87,   88,   18,   77,   77,   92,  113,   94,
 /*   270 */    98,   96,   83,   78,   99,  113,   81,  102,  103,  104,
 /*   280 */   105,  106,   77,  113,  112,  113,  113,  113,   83,   84,
 /*   290 */    85,   86,   87,   88,  106,  106,  113,   92,   77,   94,
 /*   300 */   113,   96,  113,   82,   83,  113,   77,  102,  103,  104,
 /*   310 */   105,  106,   83,   84,   85,   86,   87,   88,  113,  113,
 /*   320 */   113,   92,  113,   94,  113,   96,  113,  106,  113,  113,
 /*   330 */    77,  102,  103,  104,  105,  106,   83,   84,   85,   86,
 /*   340 */    87,   88,  113,  113,   77,   92,  113,   94,  113,   96,
 /*   350 */    83,  113,  113,  113,  113,  102,  103,  104,  105,  106,
 /*   360 */    77,  113,  113,  113,  113,  113,   83,  113,   85,   86,
 /*   370 */    87,   88,  113,  106,  113,   92,   77,   94,  113,   96,
 /*   380 */   113,  113,   83,  113,   77,  102,  103,  104,  105,  106,
 /*   390 */    83,  113,   85,   86,   87,   88,  113,  113,  113,   92,
 /*   400 */   113,   94,  113,  113,  113,  106,  113,  113,   77,  102,
 /*   410 */   103,  104,  105,  106,   83,   77,   85,   86,   87,   88,
 /*   420 */     8,   83,  113,   92,  113,  113,  113,  113,  113,  113,
 /*   430 */   113,  113,  113,  102,  103,  104,  105,  106,  113,  113,
 /*   440 */   113,  113,   30,  113,  106,   33,   34,  113,  113,  113,
 /*   450 */   113,   39,   40,   41,   42,   43,   44,   45,  113,  113,
 /*   460 */   113,  113,   77,  113,  113,  113,  113,  113,   83,  113,
 /*   470 */    85,   86,   87,   88,  113,  113,   20,   21,   22,   23,
 /*   480 */    24,   25,  113,  113,  113,  113,  113,  102,  103,  104,
 /*   490 */   105,  106,   36,   77,   77,   77,  113,  113,  113,   83,
 /*   500 */    83,   83,  113,  113,  113,   49,   50,  113,  113,  113,
 /*   510 */   113,  113,  113,  113,  113,  113,  113,  113,  113,  113,
 /*   520 */   113,  113,  106,  106,  106,
);
    const YY_SHIFT_USE_DFLT = -33;
    const YY_SHIFT_MAX = 64;
    static public $yy_shift_ofst = array(
 /*     0 */     3,  -17,  -17,   59,   59,   59,   59,   59,   59,   59,
 /*    10 */    59,   59,   51,   51,  412,  412,  456,   64,   64,   51,
 /*    20 */    51,   51,   51,   51,   51,   51,   51,   51,   51,  119,
 /*    30 */    15,  -27,  -27,  -27,  -27,  -32,   -1,  -32,   51,   51,
 /*    40 */   183,   51,  183,   51,  183,   51,   51,   52,   52,  192,
 /*    50 */   131,  131,  131,   51,  131,   84,  228,  224,  246,   21,
 /*    60 */   155,  130,  121,  150,  149,
);
    const YY_REDUCE_USE_DFLT = -78;
    const YY_REDUCE_MAX = 54;
    static public $yy_reduce_ofst = array(
 /*     0 */    58,   88,  118,  175,  151,  253,  205,  229,  283,  307,
 /*    10 */   331,  385,  221,  134,  120,  120,   55,   90,   90,  416,
 /*    20 */   417,  418,  267,  189,  136,  102,  299,  338,    0,  172,
 /*    30 */   195,  172,  172,  172,  172,   89,   27,   89,    1,   32,
 /*    40 */    70,   93,  170,   81,  163,  188,   72,  117,  117,  105,
 /*    50 */    60,   61,    6,  -77,    2,
);
    static public $yyExpectedTokens = array(
        /* 0 */ array(1, 17, 23, 24, 25, 27, 28, 29, 30, 31, 32, 53, 54, 55, 56, 57, 58, 59, 60, 61, 62, 63, 64, 65, 66, 67, 68, 69, 70, 71, 72, ),
        /* 1 */ array(17, 19, 23, 24, 25, 27, 28, 29, 30, 31, 32, 53, 54, 55, 56, 57, 58, 59, 60, 61, 62, 63, 64, 65, 66, 67, 68, 69, 70, 71, 72, ),
        /* 2 */ array(17, 19, 23, 24, 25, 27, 28, 29, 30, 31, 32, 53, 54, 55, 56, 57, 58, 59, 60, 61, 62, 63, 64, 65, 66, 67, 68, 69, 70, 71, 72, ),
        /* 3 */ array(17, 23, 24, 25, 27, 28, 29, 30, 31, 32, 53, 54, 55, 56, 57, 58, 59, 60, 61, 62, 63, 64, 65, 66, 67, 68, 69, 70, 71, 72, ),
        /* 4 */ array(17, 23, 24, 25, 27, 28, 29, 30, 31, 32, 53, 54, 55, 56, 57, 58, 59, 60, 61, 62, 63, 64, 65, 66, 67, 68, 69, 70, 71, 72, ),
        /* 5 */ array(17, 23, 24, 25, 27, 28, 29, 30, 31, 32, 53, 54, 55, 56, 57, 58, 59, 60, 61, 62, 63, 64, 65, 66, 67, 68, 69, 70, 71, 72, ),
        /* 6 */ array(17, 23, 24, 25, 27, 28, 29, 30, 31, 32, 53, 54, 55, 56, 57, 58, 59, 60, 61, 62, 63, 64, 65, 66, 67, 68, 69, 70, 71, 72, ),
        /* 7 */ array(17, 23, 24, 25, 27, 28, 29, 30, 31, 32, 53, 54, 55, 56, 57, 58, 59, 60, 61, 62, 63, 64, 65, 66, 67, 68, 69, 70, 71, 72, ),
        /* 8 */ array(17, 23, 24, 25, 27, 28, 29, 30, 31, 32, 53, 54, 55, 56, 57, 58, 59, 60, 61, 62, 63, 64, 65, 66, 67, 68, 69, 70, 71, 72, ),
        /* 9 */ array(17, 23, 24, 25, 27, 28, 29, 30, 31, 32, 53, 54, 55, 56, 57, 58, 59, 60, 61, 62, 63, 64, 65, 66, 67, 68, 69, 70, 71, 72, ),
        /* 10 */ array(17, 23, 24, 25, 27, 28, 29, 30, 31, 32, 53, 54, 55, 56, 57, 58, 59, 60, 61, 62, 63, 64, 65, 66, 67, 68, 69, 70, 71, 72, ),
        /* 11 */ array(17, 23, 24, 25, 27, 28, 29, 30, 31, 32, 53, 54, 55, 56, 57, 58, 59, 60, 61, 62, 63, 64, 65, 66, 67, 68, 69, 70, 71, 72, ),
        /* 12 */ array(28, ),
        /* 13 */ array(28, ),
        /* 14 */ array(8, 30, 33, 34, 39, 40, 41, 42, 43, 44, 45, ),
        /* 15 */ array(8, 30, 33, 34, 39, 40, 41, 42, 43, 44, 45, ),
        /* 16 */ array(20, 21, 22, 23, 24, 25, 36, 49, 50, ),
        /* 17 */ array(37, 38, 46, 47, ),
        /* 18 */ array(37, 38, 46, 47, ),
        /* 19 */ array(28, ),
        /* 20 */ array(28, ),
        /* 21 */ array(28, ),
        /* 22 */ array(28, ),
        /* 23 */ array(28, ),
        /* 24 */ array(28, ),
        /* 25 */ array(28, ),
        /* 26 */ array(28, ),
        /* 27 */ array(28, ),
        /* 28 */ array(28, ),
        /* 29 */ array(18, 36, 49, 50, ),
        /* 30 */ array(2, 3, 4, 6, ),
        /* 31 */ array(36, 49, 50, ),
        /* 32 */ array(36, 49, 50, ),
        /* 33 */ array(36, 49, 50, ),
        /* 34 */ array(36, 49, 50, ),
        /* 35 */ array(35, 48, ),
        /* 36 */ array(2, 6, ),
        /* 37 */ array(35, 48, ),
        /* 38 */ array(28, ),
        /* 39 */ array(28, ),
        /* 40 */ array(6, ),
        /* 41 */ array(28, ),
        /* 42 */ array(6, ),
        /* 43 */ array(28, ),
        /* 44 */ array(6, ),
        /* 45 */ array(28, ),
        /* 46 */ array(28, ),
        /* 47 */ array(51, 52, ),
        /* 48 */ array(51, 52, ),
        /* 49 */ array(17, ),
        /* 50 */ array(5, ),
        /* 51 */ array(5, ),
        /* 52 */ array(5, ),
        /* 53 */ array(28, ),
        /* 54 */ array(5, ),
        /* 55 */ array(8, 9, 10, 11, 12, 13, 14, 15, 16, ),
        /* 56 */ array(4, 18, ),
        /* 57 */ array(2, 7, ),
        /* 58 */ array(4, 18, ),
        /* 59 */ array(3, 4, ),
        /* 60 */ array(26, ),
        /* 61 */ array(29, ),
        /* 62 */ array(26, ),
        /* 63 */ array(7, ),
        /* 64 */ array(17, ),
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
);
    static public $yy_default = array(
 /*     0 */   281,  206,  281,  281,  281,  281,  281,  281,  281,  281,
 /*    10 */   281,  281,  281,  281,  200,  199,  281,  198,  197,  281,
 /*    20 */   281,  281,  281,  281,  281,  281,  281,  281,  281,  281,
 /*    30 */   176,  205,  188,  209,  204,  202,  176,  201,  281,  281,
 /*    40 */   176,  281,  175,  281,  176,  281,  281,  196,  195,  281,
 /*    50 */   173,  173,  173,  281,  173,  281,  281,  281,  281,  281,
 /*    60 */   221,  281,  281,  281,  281,  186,  185,  208,  169,  187,
 /*    70 */   223,  237,  178,  238,  253,  171,  184,  203,  211,  181,
 /*    80 */   168,  182,  167,  174,  179,  180,  207,  216,  177,  210,
 /*    90 */   212,  213,  215,  214,  183,  232,  224,  226,  227,  225,
 /*   100 */   222,  219,  220,  228,  229,  262,  263,  264,  261,  260,
 /*   110 */   258,  259,  218,  217,  190,  191,  192,  189,  172,  165,
 /*   120 */   166,  193,  239,  256,  257,  194,  255,  240,  254,  265,
 /*   130 */   266,  233,  234,  235,  164,  252,  242,  251,  236,  243,
 /*   140 */   248,  249,  247,  246,  244,  245,  241,  231,  271,  272,
 /*   150 */   273,  270,  269,  267,  268,  274,  275,  280,  230,  279,
 /*   160 */   278,  276,  277,  250,
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
    const YYNOCODE = 114;
    const YYSTACKDEPTH = 100;
    const YYNSTATE = 164;
    const YYNRULE = 117;
    const YYERRORSYMBOL = 73;
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
  'NAME',          'NUMVAL',        'MATH_MINUS',    'HEXVAL',      
  'STRVAL',        'REGEXP',        'NOT_EQ',        'LOG_AND',     
  'LOG_OR',        'MATH_DIV',      'MATH_MULT',     'MATH_PLUS',   
  'GT',            'LT',            'GE',            'LE',          
  'LIKE',          'NOT_LIKE',      'BITWISE_LEFT_SHIFT',  'BITWISE_RIGHT_SHIFT',
  'BITWISE_AND',   'BITWISE_OR',    'BITWISE_XOR',   'IN',          
  'NOT_IN',        'F_IF',          'F_ELT',         'F_COALESCE',  
  'F_ISNULL',      'F_CONCAT',      'F_SUBSTR',      'F_TRIM',      
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
  'bitwise_operator1',  'num_operator2',  'str_operator',  'bitwise_operator3',
  'bitwise_operator4',
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
 /*  63 */ "num_value ::= MATH_MINUS NUMVAL",
 /*  64 */ "num_value ::= HEXVAL",
 /*  65 */ "str_value ::= STRVAL",
 /*  66 */ "operator1 ::= num_operator1",
 /*  67 */ "operator1 ::= bitwise_operator1",
 /*  68 */ "operator2 ::= num_operator2",
 /*  69 */ "operator2 ::= str_operator",
 /*  70 */ "operator2 ::= REGEXP",
 /*  71 */ "operator2 ::= EQ",
 /*  72 */ "operator2 ::= NOT_EQ",
 /*  73 */ "operator3 ::= LOG_AND",
 /*  74 */ "operator3 ::= bitwise_operator3",
 /*  75 */ "operator4 ::= LOG_OR",
 /*  76 */ "operator4 ::= bitwise_operator4",
 /*  77 */ "num_operator1 ::= MATH_DIV",
 /*  78 */ "num_operator1 ::= MATH_MULT",
 /*  79 */ "num_operator2 ::= MATH_PLUS",
 /*  80 */ "num_operator2 ::= MATH_MINUS",
 /*  81 */ "num_operator2 ::= GT",
 /*  82 */ "num_operator2 ::= LT",
 /*  83 */ "num_operator2 ::= GE",
 /*  84 */ "num_operator2 ::= LE",
 /*  85 */ "str_operator ::= LIKE",
 /*  86 */ "str_operator ::= NOT_LIKE",
 /*  87 */ "bitwise_operator1 ::= BITWISE_LEFT_SHIFT",
 /*  88 */ "bitwise_operator1 ::= BITWISE_RIGHT_SHIFT",
 /*  89 */ "bitwise_operator3 ::= BITWISE_AND",
 /*  90 */ "bitwise_operator4 ::= BITWISE_OR",
 /*  91 */ "bitwise_operator4 ::= BITWISE_XOR",
 /*  92 */ "list_operator ::= IN",
 /*  93 */ "list_operator ::= NOT_IN",
 /*  94 */ "func_name ::= F_IF",
 /*  95 */ "func_name ::= F_ELT",
 /*  96 */ "func_name ::= F_COALESCE",
 /*  97 */ "func_name ::= F_ISNULL",
 /*  98 */ "func_name ::= F_CONCAT",
 /*  99 */ "func_name ::= F_SUBSTR",
 /* 100 */ "func_name ::= F_TRIM",
 /* 101 */ "func_name ::= F_DATE",
 /* 102 */ "func_name ::= F_DATE_FORMAT",
 /* 103 */ "func_name ::= F_CURRENT_DATE",
 /* 104 */ "func_name ::= F_NOW",
 /* 105 */ "func_name ::= F_TIME",
 /* 106 */ "func_name ::= F_TO_DAYS",
 /* 107 */ "func_name ::= F_FROM_DAYS",
 /* 108 */ "func_name ::= F_YEAR",
 /* 109 */ "func_name ::= F_MONTH",
 /* 110 */ "func_name ::= F_DAY",
 /* 111 */ "func_name ::= F_DATE_ADD",
 /* 112 */ "func_name ::= F_DATE_SUB",
 /* 113 */ "func_name ::= F_ROUND",
 /* 114 */ "func_name ::= F_FLOOR",
 /* 115 */ "func_name ::= F_INET_ATON",
 /* 116 */ "func_name ::= F_INET_NTOA",
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
  array( 'lhs' => 74, 'rhs' => 1 ),
  array( 'lhs' => 74, 'rhs' => 1 ),
  array( 'lhs' => 75, 'rhs' => 4 ),
  array( 'lhs' => 75, 'rhs' => 6 ),
  array( 'lhs' => 75, 'rhs' => 6 ),
  array( 'lhs' => 75, 'rhs' => 8 ),
  array( 'lhs' => 80, 'rhs' => 1 ),
  array( 'lhs' => 80, 'rhs' => 3 ),
  array( 'lhs' => 79, 'rhs' => 2 ),
  array( 'lhs' => 79, 'rhs' => 0 ),
  array( 'lhs' => 78, 'rhs' => 2 ),
  array( 'lhs' => 78, 'rhs' => 1 ),
  array( 'lhs' => 78, 'rhs' => 0 ),
  array( 'lhs' => 81, 'rhs' => 6 ),
  array( 'lhs' => 81, 'rhs' => 4 ),
  array( 'lhs' => 82, 'rhs' => 3 ),
  array( 'lhs' => 82, 'rhs' => 3 ),
  array( 'lhs' => 82, 'rhs' => 3 ),
  array( 'lhs' => 82, 'rhs' => 3 ),
  array( 'lhs' => 82, 'rhs' => 3 ),
  array( 'lhs' => 82, 'rhs' => 3 ),
  array( 'lhs' => 82, 'rhs' => 3 ),
  array( 'lhs' => 82, 'rhs' => 3 ),
  array( 'lhs' => 82, 'rhs' => 3 ),
  array( 'lhs' => 76, 'rhs' => 1 ),
  array( 'lhs' => 85, 'rhs' => 1 ),
  array( 'lhs' => 85, 'rhs' => 1 ),
  array( 'lhs' => 85, 'rhs' => 1 ),
  array( 'lhs' => 85, 'rhs' => 4 ),
  array( 'lhs' => 85, 'rhs' => 3 ),
  array( 'lhs' => 85, 'rhs' => 3 ),
  array( 'lhs' => 92, 'rhs' => 1 ),
  array( 'lhs' => 92, 'rhs' => 3 ),
  array( 'lhs' => 94, 'rhs' => 1 ),
  array( 'lhs' => 94, 'rhs' => 3 ),
  array( 'lhs' => 96, 'rhs' => 1 ),
  array( 'lhs' => 96, 'rhs' => 3 ),
  array( 'lhs' => 84, 'rhs' => 1 ),
  array( 'lhs' => 84, 'rhs' => 3 ),
  array( 'lhs' => 91, 'rhs' => 3 ),
  array( 'lhs' => 99, 'rhs' => 1 ),
  array( 'lhs' => 99, 'rhs' => 3 ),
  array( 'lhs' => 89, 'rhs' => 0 ),
  array( 'lhs' => 89, 'rhs' => 1 ),
  array( 'lhs' => 89, 'rhs' => 3 ),
  array( 'lhs' => 100, 'rhs' => 1 ),
  array( 'lhs' => 100, 'rhs' => 3 ),
  array( 'lhs' => 101, 'rhs' => 1 ),
  array( 'lhs' => 101, 'rhs' => 1 ),
  array( 'lhs' => 101, 'rhs' => 1 ),
  array( 'lhs' => 101, 'rhs' => 1 ),
  array( 'lhs' => 101, 'rhs' => 1 ),
  array( 'lhs' => 101, 'rhs' => 1 ),
  array( 'lhs' => 86, 'rhs' => 1 ),
  array( 'lhs' => 86, 'rhs' => 1 ),
  array( 'lhs' => 102, 'rhs' => 1 ),
  array( 'lhs' => 103, 'rhs' => 1 ),
  array( 'lhs' => 83, 'rhs' => 1 ),
  array( 'lhs' => 83, 'rhs' => 3 ),
  array( 'lhs' => 77, 'rhs' => 1 ),
  array( 'lhs' => 87, 'rhs' => 1 ),
  array( 'lhs' => 106, 'rhs' => 1 ),
  array( 'lhs' => 104, 'rhs' => 1 ),
  array( 'lhs' => 104, 'rhs' => 2 ),
  array( 'lhs' => 104, 'rhs' => 1 ),
  array( 'lhs' => 105, 'rhs' => 1 ),
  array( 'lhs' => 93, 'rhs' => 1 ),
  array( 'lhs' => 93, 'rhs' => 1 ),
  array( 'lhs' => 95, 'rhs' => 1 ),
  array( 'lhs' => 95, 'rhs' => 1 ),
  array( 'lhs' => 95, 'rhs' => 1 ),
  array( 'lhs' => 95, 'rhs' => 1 ),
  array( 'lhs' => 95, 'rhs' => 1 ),
  array( 'lhs' => 97, 'rhs' => 1 ),
  array( 'lhs' => 97, 'rhs' => 1 ),
  array( 'lhs' => 98, 'rhs' => 1 ),
  array( 'lhs' => 98, 'rhs' => 1 ),
  array( 'lhs' => 107, 'rhs' => 1 ),
  array( 'lhs' => 107, 'rhs' => 1 ),
  array( 'lhs' => 109, 'rhs' => 1 ),
  array( 'lhs' => 109, 'rhs' => 1 ),
  array( 'lhs' => 109, 'rhs' => 1 ),
  array( 'lhs' => 109, 'rhs' => 1 ),
  array( 'lhs' => 109, 'rhs' => 1 ),
  array( 'lhs' => 109, 'rhs' => 1 ),
  array( 'lhs' => 110, 'rhs' => 1 ),
  array( 'lhs' => 110, 'rhs' => 1 ),
  array( 'lhs' => 108, 'rhs' => 1 ),
  array( 'lhs' => 108, 'rhs' => 1 ),
  array( 'lhs' => 111, 'rhs' => 1 ),
  array( 'lhs' => 112, 'rhs' => 1 ),
  array( 'lhs' => 112, 'rhs' => 1 ),
  array( 'lhs' => 90, 'rhs' => 1 ),
  array( 'lhs' => 90, 'rhs' => 1 ),
  array( 'lhs' => 88, 'rhs' => 1 ),
  array( 'lhs' => 88, 'rhs' => 1 ),
  array( 'lhs' => 88, 'rhs' => 1 ),
  array( 'lhs' => 88, 'rhs' => 1 ),
  array( 'lhs' => 88, 'rhs' => 1 ),
  array( 'lhs' => 88, 'rhs' => 1 ),
  array( 'lhs' => 88, 'rhs' => 1 ),
  array( 'lhs' => 88, 'rhs' => 1 ),
  array( 'lhs' => 88, 'rhs' => 1 ),
  array( 'lhs' => 88, 'rhs' => 1 ),
  array( 'lhs' => 88, 'rhs' => 1 ),
  array( 'lhs' => 88, 'rhs' => 1 ),
  array( 'lhs' => 88, 'rhs' => 1 ),
  array( 'lhs' => 88, 'rhs' => 1 ),
  array( 'lhs' => 88, 'rhs' => 1 ),
  array( 'lhs' => 88, 'rhs' => 1 ),
  array( 'lhs' => 88, 'rhs' => 1 ),
  array( 'lhs' => 88, 'rhs' => 1 ),
  array( 'lhs' => 88, 'rhs' => 1 ),
  array( 'lhs' => 88, 'rhs' => 1 ),
  array( 'lhs' => 88, 'rhs' => 1 ),
  array( 'lhs' => 88, 'rhs' => 1 ),
  array( 'lhs' => 88, 'rhs' => 1 ),
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
        107 => 59,
        108 => 59,
        109 => 59,
        110 => 59,
        111 => 59,
        112 => 59,
        113 => 59,
        114 => 59,
        115 => 59,
        116 => 59,
        60 => 60,
        61 => 61,
        62 => 62,
        63 => 63,
        64 => 64,
        65 => 65,
        66 => 66,
        67 => 66,
        68 => 66,
        69 => 66,
        70 => 66,
        71 => 66,
        72 => 66,
        73 => 66,
        74 => 66,
        75 => 66,
        76 => 66,
        77 => 66,
        78 => 66,
        79 => 66,
        80 => 66,
        81 => 66,
        82 => 66,
        83 => 66,
        84 => 66,
        85 => 66,
        86 => 66,
        87 => 66,
        88 => 66,
        89 => 66,
        90 => 66,
        91 => 66,
        92 => 66,
        93 => 66,
    );
    /* Beginning here are the reduction cases.  A typical example
    ** follows:
    **  #line <lineno> <grammarfile>
    **   function yy_r0($yymsp){ ... }           // User supplied code
    **  #line <lineno> <thisfile>
    */
#line 29 "oql-parser.y"
    function yy_r0(){ $this->my_result = $this->yystack[$this->yyidx + 0]->minor;     }
#line 1431 "oql-parser.php"
#line 32 "oql-parser.y"
    function yy_r2(){
	$this->_retvalue = new OqlObjectQuery($this->yystack[$this->yyidx + -2]->minor, $this->yystack[$this->yyidx + -2]->minor, $this->yystack[$this->yyidx + 0]->minor, $this->yystack[$this->yyidx + -1]->minor, array($this->yystack[$this->yyidx + -2]->minor));
    }
#line 1436 "oql-parser.php"
#line 35 "oql-parser.y"
    function yy_r3(){
	$this->_retvalue = new OqlObjectQuery($this->yystack[$this->yyidx + -4]->minor, $this->yystack[$this->yyidx + -2]->minor, $this->yystack[$this->yyidx + 0]->minor, $this->yystack[$this->yyidx + -1]->minor, array($this->yystack[$this->yyidx + -2]->minor));
    }
#line 1441 "oql-parser.php"
#line 39 "oql-parser.y"
    function yy_r4(){
	$this->_retvalue = new OqlObjectQuery($this->yystack[$this->yyidx + -2]->minor, $this->yystack[$this->yyidx + -2]->minor, $this->yystack[$this->yyidx + 0]->minor, $this->yystack[$this->yyidx + -1]->minor, $this->yystack[$this->yyidx + -4]->minor);
    }
#line 1446 "oql-parser.php"
#line 42 "oql-parser.y"
    function yy_r5(){
	$this->_retvalue = new OqlObjectQuery($this->yystack[$this->yyidx + -4]->minor, $this->yystack[$this->yyidx + -2]->minor, $this->yystack[$this->yyidx + 0]->minor, $this->yystack[$this->yyidx + -1]->minor, $this->yystack[$this->yyidx + -6]->minor);
    }
#line 1451 "oql-parser.php"
#line 47 "oql-parser.y"
    function yy_r6(){
	$this->_retvalue = array($this->yystack[$this->yyidx + 0]->minor);
    }
#line 1456 "oql-parser.php"
#line 50 "oql-parser.y"
    function yy_r7(){
	array_push($this->yystack[$this->yyidx + -2]->minor, $this->yystack[$this->yyidx + 0]->minor);
	$this->_retvalue = $this->yystack[$this->yyidx + -2]->minor;
    }
#line 1462 "oql-parser.php"
#line 55 "oql-parser.y"
    function yy_r8(){ $this->_retvalue = $this->yystack[$this->yyidx + 0]->minor;    }
#line 1465 "oql-parser.php"
#line 56 "oql-parser.y"
    function yy_r9(){ $this->_retvalue = null;    }
#line 1468 "oql-parser.php"
#line 58 "oql-parser.y"
    function yy_r10(){
	// insert the join statement on top of the existing list
	array_unshift($this->yystack[$this->yyidx + 0]->minor, $this->yystack[$this->yyidx + -1]->minor);
	// and return the updated array
	$this->_retvalue = $this->yystack[$this->yyidx + 0]->minor;
    }
#line 1476 "oql-parser.php"
#line 64 "oql-parser.y"
    function yy_r11(){
	$this->_retvalue = Array($this->yystack[$this->yyidx + 0]->minor);
    }
#line 1481 "oql-parser.php"
#line 70 "oql-parser.y"
    function yy_r13(){
	// create an array with one single item
	$this->_retvalue = new OqlJoinSpec($this->yystack[$this->yyidx + -4]->minor, $this->yystack[$this->yyidx + -2]->minor, $this->yystack[$this->yyidx + 0]->minor);
    }
#line 1487 "oql-parser.php"
#line 75 "oql-parser.y"
    function yy_r14(){
	// create an array with one single item
	$this->_retvalue = new OqlJoinSpec($this->yystack[$this->yyidx + -2]->minor, $this->yystack[$this->yyidx + -2]->minor, $this->yystack[$this->yyidx + 0]->minor);
    }
#line 1493 "oql-parser.php"
#line 80 "oql-parser.y"
    function yy_r15(){ $this->_retvalue = new BinaryOqlExpression($this->yystack[$this->yyidx + -2]->minor, '=', $this->yystack[$this->yyidx + 0]->minor);     }
#line 1496 "oql-parser.php"
#line 81 "oql-parser.y"
    function yy_r16(){ $this->_retvalue = new BinaryOqlExpression($this->yystack[$this->yyidx + -2]->minor, 'BELOW', $this->yystack[$this->yyidx + 0]->minor);     }
#line 1499 "oql-parser.php"
#line 82 "oql-parser.y"
    function yy_r17(){ $this->_retvalue = new BinaryOqlExpression($this->yystack[$this->yyidx + -2]->minor, 'BELOW_STRICT', $this->yystack[$this->yyidx + 0]->minor);     }
#line 1502 "oql-parser.php"
#line 83 "oql-parser.y"
    function yy_r18(){ $this->_retvalue = new BinaryOqlExpression($this->yystack[$this->yyidx + -2]->minor, 'NOT_BELOW', $this->yystack[$this->yyidx + 0]->minor);     }
#line 1505 "oql-parser.php"
#line 84 "oql-parser.y"
    function yy_r19(){ $this->_retvalue = new BinaryOqlExpression($this->yystack[$this->yyidx + -2]->minor, 'NOT_BELOW_STRICT', $this->yystack[$this->yyidx + 0]->minor);     }
#line 1508 "oql-parser.php"
#line 85 "oql-parser.y"
    function yy_r20(){ $this->_retvalue = new BinaryOqlExpression($this->yystack[$this->yyidx + -2]->minor, 'ABOVE', $this->yystack[$this->yyidx + 0]->minor);     }
#line 1511 "oql-parser.php"
#line 86 "oql-parser.y"
    function yy_r21(){ $this->_retvalue = new BinaryOqlExpression($this->yystack[$this->yyidx + -2]->minor, 'ABOVE_STRICT', $this->yystack[$this->yyidx + 0]->minor);     }
#line 1514 "oql-parser.php"
#line 87 "oql-parser.y"
    function yy_r22(){ $this->_retvalue = new BinaryOqlExpression($this->yystack[$this->yyidx + -2]->minor, 'NOT_ABOVE', $this->yystack[$this->yyidx + 0]->minor);     }
#line 1517 "oql-parser.php"
#line 88 "oql-parser.y"
    function yy_r23(){ $this->_retvalue = new BinaryOqlExpression($this->yystack[$this->yyidx + -2]->minor, 'NOT_ABOVE_STRICT', $this->yystack[$this->yyidx + 0]->minor);     }
#line 1520 "oql-parser.php"
#line 90 "oql-parser.y"
    function yy_r24(){ $this->_retvalue = $this->yystack[$this->yyidx + 0]->minor;     }
#line 1523 "oql-parser.php"
#line 95 "oql-parser.y"
    function yy_r28(){ $this->_retvalue = new FunctionOqlExpression($this->yystack[$this->yyidx + -3]->minor, $this->yystack[$this->yyidx + -1]->minor);     }
#line 1526 "oql-parser.php"
#line 96 "oql-parser.y"
    function yy_r29(){ $this->_retvalue = $this->yystack[$this->yyidx + -1]->minor;     }
#line 1529 "oql-parser.php"
#line 97 "oql-parser.y"
    function yy_r30(){ $this->_retvalue = new BinaryOqlExpression($this->yystack[$this->yyidx + -2]->minor, $this->yystack[$this->yyidx + -1]->minor, $this->yystack[$this->yyidx + 0]->minor);     }
#line 1532 "oql-parser.php"
#line 112 "oql-parser.y"
    function yy_r39(){
	$this->_retvalue = new ListOqlExpression($this->yystack[$this->yyidx + -1]->minor);
    }
#line 1537 "oql-parser.php"
#line 123 "oql-parser.y"
    function yy_r42(){
	$this->_retvalue = array();
    }
#line 1542 "oql-parser.php"
#line 134 "oql-parser.y"
    function yy_r46(){ $this->_retvalue = new IntervalOqlExpression($this->yystack[$this->yyidx + -1]->minor, $this->yystack[$this->yyidx + 0]->minor);     }
#line 1545 "oql-parser.php"
#line 146 "oql-parser.y"
    function yy_r55(){ $this->_retvalue = new ScalarOqlExpression($this->yystack[$this->yyidx + 0]->minor);     }
#line 1548 "oql-parser.php"
#line 149 "oql-parser.y"
    function yy_r57(){ $this->_retvalue = new FieldOqlExpression($this->yystack[$this->yyidx + 0]->minor);     }
#line 1551 "oql-parser.php"
#line 150 "oql-parser.y"
    function yy_r58(){ $this->_retvalue = new FieldOqlExpression($this->yystack[$this->yyidx + 0]->minor, $this->yystack[$this->yyidx + -2]->minor);     }
#line 1554 "oql-parser.php"
#line 151 "oql-parser.y"
    function yy_r59(){ $this->_retvalue=$this->yystack[$this->yyidx + 0]->minor;     }
#line 1557 "oql-parser.php"
#line 154 "oql-parser.y"
    function yy_r60(){ $this->_retvalue = new VariableOqlExpression(substr($this->yystack[$this->yyidx + 0]->minor, 1));     }
#line 1560 "oql-parser.php"
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
#line 1573 "oql-parser.php"
#line 167 "oql-parser.y"
    function yy_r62(){$this->_retvalue=(int)$this->yystack[$this->yyidx + 0]->minor;    }
#line 1576 "oql-parser.php"
#line 168 "oql-parser.y"
    function yy_r63(){$this->_retvalue=(int)-$this->yystack[$this->yyidx + 0]->minor;    }
#line 1579 "oql-parser.php"
#line 169 "oql-parser.y"
    function yy_r64(){$this->_retvalue=new OqlHexValue($this->yystack[$this->yyidx + 0]->minor);    }
#line 1582 "oql-parser.php"
#line 170 "oql-parser.y"
    function yy_r65(){$this->_retvalue=stripslashes(substr($this->yystack[$this->yyidx + 0]->minor, 1, strlen($this->yystack[$this->yyidx + 0]->minor) - 2));    }
#line 1585 "oql-parser.php"
#line 173 "oql-parser.y"
    function yy_r66(){$this->_retvalue=$this->yystack[$this->yyidx + 0]->minor;    }
#line 1588 "oql-parser.php"

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
#line 1704 "oql-parser.php"
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
#line 231 "oql-parser.y"


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

#line 1937 "oql-parser.php"

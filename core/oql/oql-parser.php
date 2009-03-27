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
    const MATH_DIV                       = 21;
    const MATH_MULT                      = 22;
    const MATH_PLUS                      = 23;
    const MATH_MINUS                     = 24;
    const GT                             = 25;
    const LT                             = 26;
    const GE                             = 27;
    const LE                             = 28;
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
    const YY_NO_ACTION = 205;
    const YY_ACCEPT_ACTION = 204;
    const YY_ERROR_ACTION = 203;

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
    const YY_SZ_ACTTAB = 419;
static public $yy_action = array(
 /*     0 */     5,   57,    8,    4,   95,   96,   97,    6,   93,   76,
 /*    10 */    77,   89,    2,   53,   86,   50,   54,   25,   52,   55,
 /*    20 */    51,   46,   47,   49,   56,   70,   94,  110,  109,  108,
 /*    30 */   107,  111,  112,  115,  114,  113,  106,   71,   98,   99,
 /*    40 */   100,  104,  103,   26,   66,   38,   42,    9,   81,    5,
 /*    50 */    62,   44,   82,   95,   96,   97,    3,   93,   76,   77,
 /*    60 */    39,  102,   92,   75,   74,   73,   72,   75,   74,   73,
 /*    70 */    72,   10,   66,   41,   91,   94,  110,  109,  108,  107,
 /*    80 */   111,  112,  115,  114,  113,  106,   71,   98,   99,  100,
 /*    90 */   104,  103,    5,   63,   90,   22,   95,   96,   97,   61,
 /*   100 */    93,   76,   77,   65,   64,   60,   83,   11,   80,   79,
 /*   110 */    91,   33,   91,   22,   21,   18,   16,   12,   94,  110,
 /*   120 */   109,  108,  107,  111,  112,  115,  114,  113,  106,   71,
 /*   130 */    98,   99,  100,  104,  103,  204,  105,   87,   42,   23,
 /*   140 */    43,   24,   66,   88,   30,   28,   84,   45,   36,    6,
 /*   150 */    22,   20,   58,   15,   32,   37,    1,   76,   77,  101,
 /*   160 */   116,   75,   74,   73,   72,   41,   13,   66,    7,  160,
 /*   170 */    67,   93,   24,   42,   35,   78,  173,  173,   88,   34,
 /*   180 */    28,   84,   45,   40,   42,  173,   20,  173,   15,   69,
 /*   190 */    37,  173,  173,  173,   59,  173,   75,   74,   73,   72,
 /*   200 */    41,   42,  173,  173,  173,  173,   88,   34,   28,   84,
 /*   210 */    45,   41,  173,  173,   20,  173,   15,  173,   37,  173,
 /*   220 */   173,  173,   68,  173,   75,   74,   73,   72,   41,  173,
 /*   230 */   173,  173,   85,   42,  173,  173,  173,  173,   88,   30,
 /*   240 */    28,   84,   45,  173,  173,  173,   20,  173,   15,  173,
 /*   250 */    37,  173,  173,  173,  173,  173,   75,   74,   73,   72,
 /*   260 */    41,   42,  173,  173,  173,  173,   88,   17,   28,   84,
 /*   270 */    45,  173,  173,  173,   20,  173,   15,   42,   37,  173,
 /*   280 */   173,   48,   44,  173,   75,   74,   73,   72,   41,  173,
 /*   290 */   173,  173,  173,   42,  173,  173,  173,  173,   88,   27,
 /*   300 */    28,   84,   45,  173,   41,  173,   20,  173,   15,  173,
 /*   310 */    37,  173,  173,  173,  173,  173,   75,   74,   73,   72,
 /*   320 */    41,   42,  173,  173,  173,  173,   88,  173,   28,   84,
 /*   330 */    45,  173,  173,  173,   20,  173,   15,  173,   31,  173,
 /*   340 */   173,  173,  173,  173,   75,   74,   73,   72,   41,  173,
 /*   350 */   173,  173,  173,   42,  173,  173,  173,  173,   88,  173,
 /*   360 */    28,   84,   45,  173,  173,  173,   20,  173,   14,  173,
 /*   370 */   173,  173,  173,  173,  173,  173,   75,   74,   73,   72,
 /*   380 */    41,   42,  173,  173,  173,  173,   88,  173,   28,   84,
 /*   390 */    45,   42,  173,  173,   19,  173,   88,  173,   29,   84,
 /*   400 */    45,  173,  173,  173,   75,   74,   73,   72,   41,  173,
 /*   410 */   173,  173,  173,  173,   75,   74,   73,   72,   41,
    );
    static public $yy_lookahead = array(
 /*     0 */     7,    6,   70,   10,   11,   12,   13,   73,   15,   16,
 /*    10 */    17,    8,    9,   18,   56,   83,   84,   54,   23,   24,
 /*    20 */    25,   26,   27,   28,   29,   30,   33,   34,   35,   36,
 /*    30 */    37,   38,   39,   40,   41,   42,   43,   44,   45,   46,
 /*    40 */    47,   48,   49,    1,   81,   54,   54,   68,   62,    7,
 /*    50 */    58,   59,   62,   11,   12,   13,    3,   15,   16,   17,
 /*    60 */    74,   82,    8,   77,   78,   79,   80,   77,   78,   79,
 /*    70 */    80,    7,   81,   81,   20,   33,   34,   35,   36,   37,
 /*    80 */    38,   39,   40,   41,   42,   43,   44,   45,   46,   47,
 /*    90 */    48,   49,    7,   55,   66,   57,   11,   12,   13,   56,
 /*   100 */    15,   16,   17,   11,   12,   13,    8,    9,   31,   32,
 /*   110 */    20,   55,   20,   57,    2,   54,    6,    5,   33,   34,
 /*   120 */    35,   36,   37,   38,   39,   40,   41,   42,   43,   44,
 /*   130 */    45,   46,   47,   48,   49,   51,   52,   53,   54,    2,
 /*   140 */    54,    4,   81,   59,   60,   61,   62,   63,   55,   73,
 /*   150 */    57,   67,   76,   69,   14,   71,    7,   16,   17,   21,
 /*   160 */    22,   77,   78,   79,   80,   81,    5,   81,   72,   14,
 /*   170 */    19,   15,    4,   54,   65,   81,   85,   85,   59,   60,
 /*   180 */    61,   62,   63,   64,   54,   85,   67,   85,   69,   59,
 /*   190 */    71,   85,   85,   85,   75,   85,   77,   78,   79,   80,
 /*   200 */    81,   54,   85,   85,   85,   85,   59,   60,   61,   62,
 /*   210 */    63,   81,   85,   85,   67,   85,   69,   85,   71,   85,
 /*   220 */    85,   85,   75,   85,   77,   78,   79,   80,   81,   85,
 /*   230 */    85,   85,   53,   54,   85,   85,   85,   85,   59,   60,
 /*   240 */    61,   62,   63,   85,   85,   85,   67,   85,   69,   85,
 /*   250 */    71,   85,   85,   85,   85,   85,   77,   78,   79,   80,
 /*   260 */    81,   54,   85,   85,   85,   85,   59,   60,   61,   62,
 /*   270 */    63,   85,   85,   85,   67,   85,   69,   54,   71,   85,
 /*   280 */    85,   58,   59,   85,   77,   78,   79,   80,   81,   85,
 /*   290 */    85,   85,   85,   54,   85,   85,   85,   85,   59,   60,
 /*   300 */    61,   62,   63,   85,   81,   85,   67,   85,   69,   85,
 /*   310 */    71,   85,   85,   85,   85,   85,   77,   78,   79,   80,
 /*   320 */    81,   54,   85,   85,   85,   85,   59,   85,   61,   62,
 /*   330 */    63,   85,   85,   85,   67,   85,   69,   85,   71,   85,
 /*   340 */    85,   85,   85,   85,   77,   78,   79,   80,   81,   85,
 /*   350 */    85,   85,   85,   54,   85,   85,   85,   85,   59,   85,
 /*   360 */    61,   62,   63,   85,   85,   85,   67,   85,   69,   85,
 /*   370 */    85,   85,   85,   85,   85,   85,   77,   78,   79,   80,
 /*   380 */    81,   54,   85,   85,   85,   85,   59,   85,   61,   62,
 /*   390 */    63,   54,   85,   85,   67,   85,   59,   85,   61,   62,
 /*   400 */    63,   85,   85,   85,   77,   78,   79,   80,   81,   85,
 /*   410 */    85,   85,   85,   85,   77,   78,   79,   80,   81,
);
    const YY_SHIFT_USE_DFLT = -8;
    const YY_SHIFT_MAX = 45;
    static public $yy_shift_ofst = array(
 /*     0 */    42,   -7,   -7,   85,   85,   85,   85,   85,   85,   85,
 /*    10 */   141,  141,  156,  156,   -5,   -5,  156,   92,  137,  138,
 /*    20 */   138,  156,  168,  156,  156,  168,  156,   54,   77,   77,
 /*    30 */    90,  151,  156,   53,   90,   64,   53,  151,  112,   98,
 /*    40 */     3,  155,  140,  161,  110,  149,
);
    const YY_REDUCE_USE_DFLT = -69;
    const YY_REDUCE_MAX = 37;
    static public $yy_reduce_ofst = array(
 /*     0 */    84,  119,  147,  179,  207,  239,  267,  299,  327,  337,
 /*    10 */   -14,  -10,  223,   -8,  -68,  -68,  130,   76,   93,  -21,
 /*    20 */   -21,   86,   38,  -37,   -9,   56,   61,  -66,  109,  109,
 /*    30 */   -66,   96,   94,   43,  -66,   28,  -42,   96,
);
    static public $yyExpectedTokens = array(
        /* 0 */ array(1, 7, 11, 12, 13, 15, 16, 17, 33, 34, 35, 36, 37, 38, 39, 40, 41, 42, 43, 44, 45, 46, 47, 48, 49, ),
        /* 1 */ array(7, 10, 11, 12, 13, 15, 16, 17, 33, 34, 35, 36, 37, 38, 39, 40, 41, 42, 43, 44, 45, 46, 47, 48, 49, ),
        /* 2 */ array(7, 10, 11, 12, 13, 15, 16, 17, 33, 34, 35, 36, 37, 38, 39, 40, 41, 42, 43, 44, 45, 46, 47, 48, 49, ),
        /* 3 */ array(7, 11, 12, 13, 15, 16, 17, 33, 34, 35, 36, 37, 38, 39, 40, 41, 42, 43, 44, 45, 46, 47, 48, 49, ),
        /* 4 */ array(7, 11, 12, 13, 15, 16, 17, 33, 34, 35, 36, 37, 38, 39, 40, 41, 42, 43, 44, 45, 46, 47, 48, 49, ),
        /* 5 */ array(7, 11, 12, 13, 15, 16, 17, 33, 34, 35, 36, 37, 38, 39, 40, 41, 42, 43, 44, 45, 46, 47, 48, 49, ),
        /* 6 */ array(7, 11, 12, 13, 15, 16, 17, 33, 34, 35, 36, 37, 38, 39, 40, 41, 42, 43, 44, 45, 46, 47, 48, 49, ),
        /* 7 */ array(7, 11, 12, 13, 15, 16, 17, 33, 34, 35, 36, 37, 38, 39, 40, 41, 42, 43, 44, 45, 46, 47, 48, 49, ),
        /* 8 */ array(7, 11, 12, 13, 15, 16, 17, 33, 34, 35, 36, 37, 38, 39, 40, 41, 42, 43, 44, 45, 46, 47, 48, 49, ),
        /* 9 */ array(7, 11, 12, 13, 15, 16, 17, 33, 34, 35, 36, 37, 38, 39, 40, 41, 42, 43, 44, 45, 46, 47, 48, 49, ),
        /* 10 */ array(16, 17, ),
        /* 11 */ array(16, 17, ),
        /* 12 */ array(15, ),
        /* 13 */ array(15, ),
        /* 14 */ array(6, 18, 23, 24, 25, 26, 27, 28, 29, 30, ),
        /* 15 */ array(6, 18, 23, 24, 25, 26, 27, 28, 29, 30, ),
        /* 16 */ array(15, ),
        /* 17 */ array(11, 12, 13, 20, ),
        /* 18 */ array(2, 4, ),
        /* 19 */ array(21, 22, ),
        /* 20 */ array(21, 22, ),
        /* 21 */ array(15, ),
        /* 22 */ array(4, ),
        /* 23 */ array(15, ),
        /* 24 */ array(15, ),
        /* 25 */ array(4, ),
        /* 26 */ array(15, ),
        /* 27 */ array(8, 20, ),
        /* 28 */ array(31, 32, ),
        /* 29 */ array(31, 32, ),
        /* 30 */ array(20, ),
        /* 31 */ array(19, ),
        /* 32 */ array(15, ),
        /* 33 */ array(3, ),
        /* 34 */ array(20, ),
        /* 35 */ array(7, ),
        /* 36 */ array(3, ),
        /* 37 */ array(19, ),
        /* 38 */ array(2, 5, ),
        /* 39 */ array(8, 9, ),
        /* 40 */ array(8, 9, ),
        /* 41 */ array(14, ),
        /* 42 */ array(14, ),
        /* 43 */ array(5, ),
        /* 44 */ array(6, ),
        /* 45 */ array(7, ),
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
);
    static public $yy_default = array(
 /*     0 */   203,  146,  203,  203,  203,  203,  203,  203,  203,  203,
 /*    10 */   203,  203,  203,  203,  140,  139,  203,  203,  125,  138,
 /*    20 */   137,  203,  124,  203,  203,  125,  203,  203,  135,  136,
 /*    30 */   129,  142,  203,  122,  149,  203,  122,  141,  203,  203,
 /*    40 */   203,  158,  203,  203,  203,  203,  176,  177,  127,  178,
 /*    50 */   165,  175,  173,  168,  166,  174,  179,  167,  150,  147,
 /*    60 */   153,  120,  126,  123,  152,  151,  160,  169,  148,  128,
 /*    70 */   180,  194,  157,  156,  155,  154,  162,  163,  159,  182,
 /*    80 */   181,  144,  145,  143,  130,  121,  119,  118,  131,  132,
 /*    90 */   134,  170,  133,  161,  183,  198,  197,  196,  195,  199,
 /*   100 */   200,  171,  164,  202,  201,  117,  193,  187,  186,  185,
 /*   110 */   184,  188,  189,  192,  191,  190,  172,
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
    const YYNOCODE = 86;
    const YYSTACKDEPTH = 100;
    const YYNSTATE = 117;
    const YYNRULE = 86;
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
  'LOG_OR',        'MATH_DIV',      'MATH_MULT',     'MATH_PLUS',   
  'MATH_MINUS',    'GT',            'LT',            'GE',          
  'LE',            'LIKE',          'NOT_LIKE',      'IN',          
  'NOT_IN',        'F_IF',          'F_ELT',         'F_COALESCE',  
  'F_CONCAT',      'F_SUBSTR',      'F_TRIM',        'F_DATE',      
  'F_DATE_FORMAT',  'F_CURRENT_DATE',  'F_NOW',         'F_TIME',      
  'F_TO_DAYS',     'F_FROM_DAYS',   'F_DATE_ADD',    'F_DATE_SUB',  
  'F_ROUND',       'F_FLOOR',       'error',         'result',      
  'query',         'condition',     'class_name',    'join_statement',
  'where_statement',  'join_item',     'join_condition',  'field_id',    
  'expression_prio4',  'expression_basic',  'scalar',        'func_name',   
  'arg_list',      'list_operator',  'list',          'expression_prio1',
  'operator1',     'expression_prio2',  'operator2',     'expression_prio3',
  'operator3',     'operator4',     'scalar_list',   'argument',    
  'interval_unit',  'num_scalar',    'str_scalar',    'num_value',   
  'str_value',     'name',          'num_operator1',  'num_operator2',
  'str_operator',
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
 /*  12 */ "condition ::= expression_prio4",
 /*  13 */ "expression_basic ::= scalar",
 /*  14 */ "expression_basic ::= field_id",
 /*  15 */ "expression_basic ::= func_name PAR_OPEN arg_list PAR_CLOSE",
 /*  16 */ "expression_basic ::= PAR_OPEN expression_prio4 PAR_CLOSE",
 /*  17 */ "expression_basic ::= expression_basic list_operator list",
 /*  18 */ "expression_prio1 ::= expression_basic",
 /*  19 */ "expression_prio1 ::= expression_prio1 operator1 expression_basic",
 /*  20 */ "expression_prio2 ::= expression_prio1",
 /*  21 */ "expression_prio2 ::= expression_prio2 operator2 expression_prio1",
 /*  22 */ "expression_prio3 ::= expression_prio2",
 /*  23 */ "expression_prio3 ::= expression_prio3 operator3 expression_prio2",
 /*  24 */ "expression_prio4 ::= expression_prio3",
 /*  25 */ "expression_prio4 ::= expression_prio4 operator4 expression_prio3",
 /*  26 */ "list ::= PAR_OPEN scalar_list PAR_CLOSE",
 /*  27 */ "scalar_list ::= scalar",
 /*  28 */ "scalar_list ::= scalar_list COMA scalar",
 /*  29 */ "arg_list ::=",
 /*  30 */ "arg_list ::= argument",
 /*  31 */ "arg_list ::= arg_list COMA argument",
 /*  32 */ "argument ::= expression_prio4",
 /*  33 */ "argument ::= INTERVAL expression_prio4 interval_unit",
 /*  34 */ "interval_unit ::= F_DAY",
 /*  35 */ "interval_unit ::= F_MONTH",
 /*  36 */ "interval_unit ::= F_YEAR",
 /*  37 */ "scalar ::= num_scalar",
 /*  38 */ "scalar ::= str_scalar",
 /*  39 */ "num_scalar ::= num_value",
 /*  40 */ "str_scalar ::= str_value",
 /*  41 */ "field_id ::= name",
 /*  42 */ "field_id ::= class_name DOT name",
 /*  43 */ "class_name ::= name",
 /*  44 */ "name ::= NAME",
 /*  45 */ "num_value ::= NUMVAL",
 /*  46 */ "str_value ::= STRVAL",
 /*  47 */ "operator1 ::= num_operator1",
 /*  48 */ "operator2 ::= num_operator2",
 /*  49 */ "operator2 ::= str_operator",
 /*  50 */ "operator2 ::= EQ",
 /*  51 */ "operator2 ::= NOT_EQ",
 /*  52 */ "operator3 ::= LOG_AND",
 /*  53 */ "operator4 ::= LOG_OR",
 /*  54 */ "num_operator1 ::= MATH_DIV",
 /*  55 */ "num_operator1 ::= MATH_MULT",
 /*  56 */ "num_operator2 ::= MATH_PLUS",
 /*  57 */ "num_operator2 ::= MATH_MINUS",
 /*  58 */ "num_operator2 ::= GT",
 /*  59 */ "num_operator2 ::= LT",
 /*  60 */ "num_operator2 ::= GE",
 /*  61 */ "num_operator2 ::= LE",
 /*  62 */ "str_operator ::= LIKE",
 /*  63 */ "str_operator ::= NOT_LIKE",
 /*  64 */ "list_operator ::= IN",
 /*  65 */ "list_operator ::= NOT_IN",
 /*  66 */ "func_name ::= F_IF",
 /*  67 */ "func_name ::= F_ELT",
 /*  68 */ "func_name ::= F_COALESCE",
 /*  69 */ "func_name ::= F_CONCAT",
 /*  70 */ "func_name ::= F_SUBSTR",
 /*  71 */ "func_name ::= F_TRIM",
 /*  72 */ "func_name ::= F_DATE",
 /*  73 */ "func_name ::= F_DATE_FORMAT",
 /*  74 */ "func_name ::= F_CURRENT_DATE",
 /*  75 */ "func_name ::= F_NOW",
 /*  76 */ "func_name ::= F_TIME",
 /*  77 */ "func_name ::= F_TO_DAYS",
 /*  78 */ "func_name ::= F_FROM_DAYS",
 /*  79 */ "func_name ::= F_YEAR",
 /*  80 */ "func_name ::= F_MONTH",
 /*  81 */ "func_name ::= F_DAY",
 /*  82 */ "func_name ::= F_DATE_ADD",
 /*  83 */ "func_name ::= F_DATE_SUB",
 /*  84 */ "func_name ::= F_ROUND",
 /*  85 */ "func_name ::= F_FLOOR",
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
  array( 'lhs' => 61, 'rhs' => 1 ),
  array( 'lhs' => 61, 'rhs' => 1 ),
  array( 'lhs' => 61, 'rhs' => 4 ),
  array( 'lhs' => 61, 'rhs' => 3 ),
  array( 'lhs' => 61, 'rhs' => 3 ),
  array( 'lhs' => 67, 'rhs' => 1 ),
  array( 'lhs' => 67, 'rhs' => 3 ),
  array( 'lhs' => 69, 'rhs' => 1 ),
  array( 'lhs' => 69, 'rhs' => 3 ),
  array( 'lhs' => 71, 'rhs' => 1 ),
  array( 'lhs' => 71, 'rhs' => 3 ),
  array( 'lhs' => 60, 'rhs' => 1 ),
  array( 'lhs' => 60, 'rhs' => 3 ),
  array( 'lhs' => 66, 'rhs' => 3 ),
  array( 'lhs' => 74, 'rhs' => 1 ),
  array( 'lhs' => 74, 'rhs' => 3 ),
  array( 'lhs' => 64, 'rhs' => 0 ),
  array( 'lhs' => 64, 'rhs' => 1 ),
  array( 'lhs' => 64, 'rhs' => 3 ),
  array( 'lhs' => 75, 'rhs' => 1 ),
  array( 'lhs' => 75, 'rhs' => 3 ),
  array( 'lhs' => 76, 'rhs' => 1 ),
  array( 'lhs' => 76, 'rhs' => 1 ),
  array( 'lhs' => 76, 'rhs' => 1 ),
  array( 'lhs' => 62, 'rhs' => 1 ),
  array( 'lhs' => 62, 'rhs' => 1 ),
  array( 'lhs' => 77, 'rhs' => 1 ),
  array( 'lhs' => 78, 'rhs' => 1 ),
  array( 'lhs' => 59, 'rhs' => 1 ),
  array( 'lhs' => 59, 'rhs' => 3 ),
  array( 'lhs' => 54, 'rhs' => 1 ),
  array( 'lhs' => 81, 'rhs' => 1 ),
  array( 'lhs' => 79, 'rhs' => 1 ),
  array( 'lhs' => 80, 'rhs' => 1 ),
  array( 'lhs' => 68, 'rhs' => 1 ),
  array( 'lhs' => 70, 'rhs' => 1 ),
  array( 'lhs' => 70, 'rhs' => 1 ),
  array( 'lhs' => 70, 'rhs' => 1 ),
  array( 'lhs' => 70, 'rhs' => 1 ),
  array( 'lhs' => 72, 'rhs' => 1 ),
  array( 'lhs' => 73, 'rhs' => 1 ),
  array( 'lhs' => 82, 'rhs' => 1 ),
  array( 'lhs' => 82, 'rhs' => 1 ),
  array( 'lhs' => 83, 'rhs' => 1 ),
  array( 'lhs' => 83, 'rhs' => 1 ),
  array( 'lhs' => 83, 'rhs' => 1 ),
  array( 'lhs' => 83, 'rhs' => 1 ),
  array( 'lhs' => 83, 'rhs' => 1 ),
  array( 'lhs' => 83, 'rhs' => 1 ),
  array( 'lhs' => 84, 'rhs' => 1 ),
  array( 'lhs' => 84, 'rhs' => 1 ),
  array( 'lhs' => 65, 'rhs' => 1 ),
  array( 'lhs' => 65, 'rhs' => 1 ),
  array( 'lhs' => 63, 'rhs' => 1 ),
  array( 'lhs' => 63, 'rhs' => 1 ),
  array( 'lhs' => 63, 'rhs' => 1 ),
  array( 'lhs' => 63, 'rhs' => 1 ),
  array( 'lhs' => 63, 'rhs' => 1 ),
  array( 'lhs' => 63, 'rhs' => 1 ),
  array( 'lhs' => 63, 'rhs' => 1 ),
  array( 'lhs' => 63, 'rhs' => 1 ),
  array( 'lhs' => 63, 'rhs' => 1 ),
  array( 'lhs' => 63, 'rhs' => 1 ),
  array( 'lhs' => 63, 'rhs' => 1 ),
  array( 'lhs' => 63, 'rhs' => 1 ),
  array( 'lhs' => 63, 'rhs' => 1 ),
  array( 'lhs' => 63, 'rhs' => 1 ),
  array( 'lhs' => 63, 'rhs' => 1 ),
  array( 'lhs' => 63, 'rhs' => 1 ),
  array( 'lhs' => 63, 'rhs' => 1 ),
  array( 'lhs' => 63, 'rhs' => 1 ),
  array( 'lhs' => 63, 'rhs' => 1 ),
  array( 'lhs' => 63, 'rhs' => 1 ),
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
        13 => 12,
        14 => 12,
        18 => 12,
        20 => 12,
        22 => 12,
        24 => 12,
        32 => 12,
        34 => 12,
        35 => 12,
        36 => 12,
        37 => 12,
        38 => 12,
        15 => 15,
        16 => 16,
        17 => 17,
        19 => 17,
        21 => 17,
        23 => 17,
        25 => 17,
        26 => 26,
        27 => 27,
        30 => 27,
        28 => 28,
        31 => 28,
        29 => 29,
        33 => 33,
        39 => 39,
        40 => 39,
        41 => 41,
        42 => 42,
        43 => 43,
        66 => 43,
        67 => 43,
        68 => 43,
        69 => 43,
        70 => 43,
        71 => 43,
        72 => 43,
        73 => 43,
        74 => 43,
        75 => 43,
        76 => 43,
        77 => 43,
        78 => 43,
        79 => 43,
        80 => 43,
        81 => 43,
        82 => 43,
        83 => 43,
        84 => 43,
        85 => 43,
        44 => 44,
        45 => 45,
        47 => 45,
        48 => 45,
        49 => 45,
        50 => 45,
        51 => 45,
        52 => 45,
        53 => 45,
        54 => 45,
        55 => 45,
        56 => 45,
        57 => 45,
        58 => 45,
        59 => 45,
        60 => 45,
        61 => 45,
        62 => 45,
        63 => 45,
        64 => 45,
        65 => 45,
        46 => 46,
    );
    /* Beginning here are the reduction cases.  A typical example
    ** follows:
    **  #line <lineno> <grammarfile>
    **   function yy_r0($yymsp){ ... }           // User supplied code
    **  #line <lineno> <thisfile>
    */
#line 7 "oql-parser.y"
    function yy_r0(){ $this->my_result = $this->yystack[$this->yyidx + 0]->minor;     }
#line 1230 "oql-parser.php"
#line 10 "oql-parser.y"
    function yy_r2(){
	$this->_retvalue = new OqlQuery($this->yystack[$this->yyidx + -2]->minor, $this->yystack[$this->yyidx + -2]->minor, $this->yystack[$this->yyidx + 0]->minor, $this->yystack[$this->yyidx + -1]->minor);
    }
#line 1235 "oql-parser.php"
#line 13 "oql-parser.y"
    function yy_r3(){
	$this->_retvalue = new OqlQuery($this->yystack[$this->yyidx + -4]->minor, $this->yystack[$this->yyidx + -2]->minor, $this->yystack[$this->yyidx + 0]->minor, $this->yystack[$this->yyidx + -1]->minor);
    }
#line 1240 "oql-parser.php"
#line 17 "oql-parser.y"
    function yy_r4(){ $this->_retvalue = $this->yystack[$this->yyidx + 0]->minor;    }
#line 1243 "oql-parser.php"
#line 18 "oql-parser.y"
    function yy_r5(){ $this->_retvalue = null;    }
#line 1246 "oql-parser.php"
#line 20 "oql-parser.y"
    function yy_r6(){
	// insert the join statement on top of the existing list
	array_unshift($this->yystack[$this->yyidx + 0]->minor, $this->yystack[$this->yyidx + -1]->minor);
	// and return the updated array
	$this->_retvalue = $this->yystack[$this->yyidx + 0]->minor;
    }
#line 1254 "oql-parser.php"
#line 26 "oql-parser.y"
    function yy_r7(){
	$this->_retvalue = Array($this->yystack[$this->yyidx + 0]->minor);
    }
#line 1259 "oql-parser.php"
#line 32 "oql-parser.y"
    function yy_r9(){
	// create an array with one single item
	$this->_retvalue = new OqlJoinSpec($this->yystack[$this->yyidx + -4]->minor, $this->yystack[$this->yyidx + -2]->minor, $this->yystack[$this->yyidx + 0]->minor);
    }
#line 1265 "oql-parser.php"
#line 37 "oql-parser.y"
    function yy_r10(){
	// create an array with one single item
	$this->_retvalue = new OqlJoinSpec($this->yystack[$this->yyidx + -2]->minor, $this->yystack[$this->yyidx + -2]->minor, $this->yystack[$this->yyidx + 0]->minor);
    }
#line 1271 "oql-parser.php"
#line 42 "oql-parser.y"
    function yy_r11(){ $this->_retvalue = new BinaryOqlExpression($this->yystack[$this->yyidx + -2]->minor, '=', $this->yystack[$this->yyidx + 0]->minor);     }
#line 1274 "oql-parser.php"
#line 44 "oql-parser.y"
    function yy_r12(){ $this->_retvalue = $this->yystack[$this->yyidx + 0]->minor;     }
#line 1277 "oql-parser.php"
#line 48 "oql-parser.y"
    function yy_r15(){ $this->_retvalue = new FunctionOqlExpression($this->yystack[$this->yyidx + -3]->minor, $this->yystack[$this->yyidx + -1]->minor);     }
#line 1280 "oql-parser.php"
#line 49 "oql-parser.y"
    function yy_r16(){ $this->_retvalue = $this->yystack[$this->yyidx + -1]->minor;     }
#line 1283 "oql-parser.php"
#line 50 "oql-parser.y"
    function yy_r17(){ $this->_retvalue = new BinaryOqlExpression($this->yystack[$this->yyidx + -2]->minor, $this->yystack[$this->yyidx + -1]->minor, $this->yystack[$this->yyidx + 0]->minor);     }
#line 1286 "oql-parser.php"
#line 65 "oql-parser.y"
    function yy_r26(){
	$this->_retvalue = new ListOqlExpression($this->yystack[$this->yyidx + -1]->minor);
    }
#line 1291 "oql-parser.php"
#line 68 "oql-parser.y"
    function yy_r27(){
	$this->_retvalue = array($this->yystack[$this->yyidx + 0]->minor);
    }
#line 1296 "oql-parser.php"
#line 71 "oql-parser.y"
    function yy_r28(){
	array_push($this->yystack[$this->yyidx + -2]->minor, $this->yystack[$this->yyidx + 0]->minor);
	$this->_retvalue = $this->yystack[$this->yyidx + -2]->minor;
    }
#line 1302 "oql-parser.php"
#line 76 "oql-parser.y"
    function yy_r29(){
	$this->_retvalue = array();
    }
#line 1307 "oql-parser.php"
#line 87 "oql-parser.y"
    function yy_r33(){ $this->_retvalue = new IntervalOqlExpression($this->yystack[$this->yyidx + -1]->minor, $this->yystack[$this->yyidx + 0]->minor);     }
#line 1310 "oql-parser.php"
#line 96 "oql-parser.y"
    function yy_r39(){ $this->_retvalue = new ScalarOqlExpression($this->yystack[$this->yyidx + 0]->minor);     }
#line 1313 "oql-parser.php"
#line 99 "oql-parser.y"
    function yy_r41(){ $this->_retvalue = new FieldOqlExpression($this->yystack[$this->yyidx + 0]->minor);     }
#line 1316 "oql-parser.php"
#line 100 "oql-parser.y"
    function yy_r42(){ $this->_retvalue = new FieldOqlExpression($this->yystack[$this->yyidx + 0]->minor, $this->yystack[$this->yyidx + -2]->minor);     }
#line 1319 "oql-parser.php"
#line 101 "oql-parser.y"
    function yy_r43(){ $this->_retvalue=$this->yystack[$this->yyidx + 0]->minor;     }
#line 1322 "oql-parser.php"
#line 103 "oql-parser.y"
    function yy_r44(){
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
#line 1335 "oql-parser.php"
#line 115 "oql-parser.y"
    function yy_r45(){$this->_retvalue=$this->yystack[$this->yyidx + 0]->minor;    }
#line 1338 "oql-parser.php"
#line 116 "oql-parser.y"
    function yy_r46(){$this->_retvalue=stripslashes(substr($this->yystack[$this->yyidx + 0]->minor, 1, strlen($this->yystack[$this->yyidx + 0]->minor) - 2));    }
#line 1341 "oql-parser.php"

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
#line 1457 "oql-parser.php"
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
}#line 164 "oql-parser.y"



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

#line 1677 "oql-parser.php"

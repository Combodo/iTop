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
    const WHERE                          =  3;
    const JOIN                           =  4;
    const ON                             =  5;
    const EQ                             =  6;
    const PAR_OPEN                       =  7;
    const PAR_CLOSE                      =  8;
    const COMA                           =  9;
    const INTERVAL                       = 10;
    const F_SECOND                       = 11;
    const F_MINUTE                       = 12;
    const F_HOUR                         = 13;
    const F_DAY                          = 14;
    const F_MONTH                        = 15;
    const F_YEAR                         = 16;
    const DOT                            = 17;
    const VARNAME                        = 18;
    const NAME                           = 19;
    const NUMVAL                         = 20;
    const STRVAL                         = 21;
    const NOT_EQ                         = 22;
    const LOG_AND                        = 23;
    const LOG_OR                         = 24;
    const MATH_DIV                       = 25;
    const MATH_MULT                      = 26;
    const MATH_PLUS                      = 27;
    const MATH_MINUS                     = 28;
    const GT                             = 29;
    const LT                             = 30;
    const GE                             = 31;
    const LE                             = 32;
    const LIKE                           = 33;
    const NOT_LIKE                       = 34;
    const IN                             = 35;
    const NOT_IN                         = 36;
    const F_IF                           = 37;
    const F_ELT                          = 38;
    const F_COALESCE                     = 39;
    const F_CONCAT                       = 40;
    const F_SUBSTR                       = 41;
    const F_TRIM                         = 42;
    const F_DATE                         = 43;
    const F_DATE_FORMAT                  = 44;
    const F_CURRENT_DATE                 = 45;
    const F_NOW                          = 46;
    const F_TIME                         = 47;
    const F_TO_DAYS                      = 48;
    const F_FROM_DAYS                    = 49;
    const F_DATE_ADD                     = 50;
    const F_DATE_SUB                     = 51;
    const F_ROUND                        = 52;
    const F_FLOOR                        = 53;
    const F_INET_ATON                    = 54;
    const F_INET_NTOA                    = 55;
    const YY_NO_ACTION = 219;
    const YY_ACCEPT_ACTION = 218;
    const YY_ERROR_ACTION = 217;

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
    const YY_SZ_ACTTAB = 432;
static public $yy_action = array(
 /*     0 */     4,   50,   81,    5,   97,    2,   70,  102,  103,   74,
 /*    10 */    10,   98,   82,   76,   80,   38,   90,   55,   79,   78,
 /*    20 */    77,   75,   57,   56,   58,   47,   48,   49,   46,   54,
 /*    30 */    99,  116,  115,  114,  113,  117,  118,  122,  121,  120,
 /*    40 */   119,  112,  111,  100,  101,  105,  106,  110,  109,   24,
 /*    50 */     6,   43,   43,   71,   86,    4,   92,   69,   29,   88,
 /*    60 */    93,   42,  102,  103,   74,   20,   98,   82,   76,   80,
 /*    70 */    79,   78,   77,   75,   68,   79,   78,   77,   75,   45,
 /*    80 */    45,   33,   64,   25,   25,   99,  116,  115,  114,  113,
 /*    90 */   117,  118,  122,  121,  120,  119,  112,  111,  100,  101,
 /*   100 */   105,  106,  110,  109,    4,   43,    8,   87,   11,   62,
 /*   110 */    41,  102,  103,   74,    9,   98,   82,   76,   80,   52,
 /*   120 */    51,   21,   85,   84,   12,   35,    7,   25,  108,   40,
 /*   130 */    18,   44,    3,   45,   99,  116,  115,  114,  113,  117,
 /*   140 */   118,  122,  121,  120,  119,  112,  111,  100,  101,  105,
 /*   150 */   106,  110,  109,  218,  104,   91,   43,   73,   73,   73,
 /*   160 */    96,   92,   37,   29,   88,   93,   42,   26,   23,   22,
 /*   170 */    19,   13,   15,  171,   31,   30,   95,   76,   80,    1,
 /*   180 */    79,   78,   77,   75,   45,   95,   72,   67,   66,   61,
 /*   190 */    60,   63,  107,  123,    6,   43,   73,   94,   16,   95,
 /*   200 */    92,   32,   29,   88,   93,   42,   39,   82,   22,   19,
 /*   210 */    36,   15,  187,   31,   83,  187,  187,   65,  187,   79,
 /*   220 */    78,   77,   75,   45,   43,  187,  187,  187,  187,   92,
 /*   230 */    32,   29,   88,   93,   42,  187,  187,  187,   19,  187,
 /*   240 */    15,  187,   31,  187,  187,  187,   59,  187,   79,   78,
 /*   250 */    77,   75,   45,  187,  187,   89,   43,  187,  187,  187,
 /*   260 */   187,   92,   37,   29,   88,   93,   42,  187,  187,  187,
 /*   270 */    19,  187,   15,  187,   31,  187,  187,  187,  187,  187,
 /*   280 */    79,   78,   77,   75,   45,   43,  187,  187,  187,  187,
 /*   290 */    92,   17,   29,   88,   93,   42,  187,  187,  187,   19,
 /*   300 */   187,   15,  187,   31,  187,  187,  187,  187,  187,   79,
 /*   310 */    78,   77,   75,   45,  187,  187,   43,  187,  187,  187,
 /*   320 */   187,   92,   28,   29,   88,   93,   42,  187,  187,  187,
 /*   330 */    19,  187,   15,  187,   31,  187,  187,  187,  187,  187,
 /*   340 */    79,   78,   77,   75,   45,   43,  187,  187,  187,  187,
 /*   350 */    92,  187,   29,   88,   93,   42,  187,  187,  187,   19,
 /*   360 */   187,   15,  187,   34,  187,  187,  187,  187,  187,   79,
 /*   370 */    78,   77,   75,   45,   43,  187,  187,  187,  187,   92,
 /*   380 */   187,   29,   88,   93,   42,  187,   43,  187,   19,  187,
 /*   390 */    14,   92,  187,   27,   88,   93,   42,  187,   79,   78,
 /*   400 */    77,   75,   45,   43,  187,  187,  187,   53,   41,  187,
 /*   410 */    79,   78,   77,   75,   45,  187,  187,  187,  187,  187,
 /*   420 */   187,  187,  187,  187,  187,  187,  187,  187,  187,  187,
 /*   430 */   187,   45,
    );
    static public $yy_lookahead = array(
 /*     0 */     7,    6,   68,   10,    8,    9,   62,   14,   15,   16,
 /*    10 */     7,   18,   19,   20,   21,   81,   62,   22,   84,   85,
 /*    20 */    86,   87,   27,   28,   29,   30,   31,   32,   33,   34,
 /*    30 */    37,   38,   39,   40,   41,   42,   43,   44,   45,   46,
 /*    40 */    47,   48,   49,   50,   51,   52,   53,   54,   55,    1,
 /*    50 */    80,   60,   60,   83,   68,    7,   65,   65,   67,   68,
 /*    60 */    69,   70,   14,   15,   16,   74,   18,   19,   20,   21,
 /*    70 */    84,   85,   86,   87,   23,   84,   85,   86,   87,   88,
 /*    80 */    88,   61,   61,   63,   63,   37,   38,   39,   40,   41,
 /*    90 */    42,   43,   44,   45,   46,   47,   48,   49,   50,   51,
 /*   100 */    52,   53,   54,   55,    7,   60,   77,    8,    9,   64,
 /*   110 */    65,   14,   15,   16,   75,   18,   19,   20,   21,   90,
 /*   120 */    91,    2,   35,   36,    5,   61,   79,   63,   89,   60,
 /*   130 */    60,   60,    3,   88,   37,   38,   39,   40,   41,   42,
 /*   140 */    43,   44,   45,   46,   47,   48,   49,   50,   51,   52,
 /*   150 */    53,   54,   55,   57,   58,   59,   60,   88,   88,   88,
 /*   160 */     8,   65,   66,   67,   68,   69,   70,    2,   60,    4,
 /*   170 */    74,    5,   76,   17,   78,   17,   24,   20,   21,    7,
 /*   180 */    84,   85,   86,   87,   88,   24,   11,   12,   13,   14,
 /*   190 */    15,   16,   25,   26,   80,   60,   88,   73,    6,   24,
 /*   200 */    65,   66,   67,   68,   69,   70,   71,   19,    4,   74,
 /*   210 */    72,   76,   92,   78,   88,   92,   92,   82,   92,   84,
 /*   220 */    85,   86,   87,   88,   60,   92,   92,   92,   92,   65,
 /*   230 */    66,   67,   68,   69,   70,   92,   92,   92,   74,   92,
 /*   240 */    76,   92,   78,   92,   92,   92,   82,   92,   84,   85,
 /*   250 */    86,   87,   88,   92,   92,   59,   60,   92,   92,   92,
 /*   260 */    92,   65,   66,   67,   68,   69,   70,   92,   92,   92,
 /*   270 */    74,   92,   76,   92,   78,   92,   92,   92,   92,   92,
 /*   280 */    84,   85,   86,   87,   88,   60,   92,   92,   92,   92,
 /*   290 */    65,   66,   67,   68,   69,   70,   92,   92,   92,   74,
 /*   300 */    92,   76,   92,   78,   92,   92,   92,   92,   92,   84,
 /*   310 */    85,   86,   87,   88,   92,   92,   60,   92,   92,   92,
 /*   320 */    92,   65,   66,   67,   68,   69,   70,   92,   92,   92,
 /*   330 */    74,   92,   76,   92,   78,   92,   92,   92,   92,   92,
 /*   340 */    84,   85,   86,   87,   88,   60,   92,   92,   92,   92,
 /*   350 */    65,   92,   67,   68,   69,   70,   92,   92,   92,   74,
 /*   360 */    92,   76,   92,   78,   92,   92,   92,   92,   92,   84,
 /*   370 */    85,   86,   87,   88,   60,   92,   92,   92,   92,   65,
 /*   380 */    92,   67,   68,   69,   70,   92,   60,   92,   74,   92,
 /*   390 */    76,   65,   92,   67,   68,   69,   70,   92,   84,   85,
 /*   400 */    86,   87,   88,   60,   92,   92,   92,   64,   65,   92,
 /*   410 */    84,   85,   86,   87,   88,   92,   92,   92,   92,   92,
 /*   420 */    92,   92,   92,   92,   92,   92,   92,   92,   92,   92,
 /*   430 */    92,   88,
);
    const YY_SHIFT_USE_DFLT = -8;
    const YY_SHIFT_MAX = 45;
    static public $yy_shift_ofst = array(
 /*     0 */    48,   -7,   -7,   97,   97,   97,   97,   97,   97,   97,
 /*    10 */   157,  157,  188,  188,   -5,   -5,  188,  175,  165,  167,
 /*    20 */   167,  188,  188,  204,  188,  204,  188,   87,  152,   87,
 /*    30 */   188,   51,  161,  129,   51,  129,    3,  161,   99,   -4,
 /*    40 */   119,  192,  172,  158,  166,  156,
);
    const YY_REDUCE_USE_DFLT = -67;
    const YY_REDUCE_MAX = 37;
    static public $yy_reduce_ofst = array(
 /*     0 */    96,  135,  164,  196,  256,  225,  285,  314,   -9,  326,
 /*    10 */   -66,  -14,  343,   45,   29,   29,   -8,  -30,   64,   39,
 /*    20 */    39,   71,   69,   20,   70,   21,  108,  138,  114,  138,
 /*    30 */   126,   47,  114,  -56,   47,  -46,  124,  114,
);
    static public $yyExpectedTokens = array(
        /* 0 */ array(1, 7, 14, 15, 16, 18, 19, 20, 21, 37, 38, 39, 40, 41, 42, 43, 44, 45, 46, 47, 48, 49, 50, 51, 52, 53, 54, 55, ),
        /* 1 */ array(7, 10, 14, 15, 16, 18, 19, 20, 21, 37, 38, 39, 40, 41, 42, 43, 44, 45, 46, 47, 48, 49, 50, 51, 52, 53, 54, 55, ),
        /* 2 */ array(7, 10, 14, 15, 16, 18, 19, 20, 21, 37, 38, 39, 40, 41, 42, 43, 44, 45, 46, 47, 48, 49, 50, 51, 52, 53, 54, 55, ),
        /* 3 */ array(7, 14, 15, 16, 18, 19, 20, 21, 37, 38, 39, 40, 41, 42, 43, 44, 45, 46, 47, 48, 49, 50, 51, 52, 53, 54, 55, ),
        /* 4 */ array(7, 14, 15, 16, 18, 19, 20, 21, 37, 38, 39, 40, 41, 42, 43, 44, 45, 46, 47, 48, 49, 50, 51, 52, 53, 54, 55, ),
        /* 5 */ array(7, 14, 15, 16, 18, 19, 20, 21, 37, 38, 39, 40, 41, 42, 43, 44, 45, 46, 47, 48, 49, 50, 51, 52, 53, 54, 55, ),
        /* 6 */ array(7, 14, 15, 16, 18, 19, 20, 21, 37, 38, 39, 40, 41, 42, 43, 44, 45, 46, 47, 48, 49, 50, 51, 52, 53, 54, 55, ),
        /* 7 */ array(7, 14, 15, 16, 18, 19, 20, 21, 37, 38, 39, 40, 41, 42, 43, 44, 45, 46, 47, 48, 49, 50, 51, 52, 53, 54, 55, ),
        /* 8 */ array(7, 14, 15, 16, 18, 19, 20, 21, 37, 38, 39, 40, 41, 42, 43, 44, 45, 46, 47, 48, 49, 50, 51, 52, 53, 54, 55, ),
        /* 9 */ array(7, 14, 15, 16, 18, 19, 20, 21, 37, 38, 39, 40, 41, 42, 43, 44, 45, 46, 47, 48, 49, 50, 51, 52, 53, 54, 55, ),
        /* 10 */ array(20, 21, ),
        /* 11 */ array(20, 21, ),
        /* 12 */ array(19, ),
        /* 13 */ array(19, ),
        /* 14 */ array(6, 22, 27, 28, 29, 30, 31, 32, 33, 34, ),
        /* 15 */ array(6, 22, 27, 28, 29, 30, 31, 32, 33, 34, ),
        /* 16 */ array(19, ),
        /* 17 */ array(11, 12, 13, 14, 15, 16, 24, ),
        /* 18 */ array(2, 4, ),
        /* 19 */ array(25, 26, ),
        /* 20 */ array(25, 26, ),
        /* 21 */ array(19, ),
        /* 22 */ array(19, ),
        /* 23 */ array(4, ),
        /* 24 */ array(19, ),
        /* 25 */ array(4, ),
        /* 26 */ array(19, ),
        /* 27 */ array(35, 36, ),
        /* 28 */ array(8, 24, ),
        /* 29 */ array(35, 36, ),
        /* 30 */ array(19, ),
        /* 31 */ array(23, ),
        /* 32 */ array(24, ),
        /* 33 */ array(3, ),
        /* 34 */ array(23, ),
        /* 35 */ array(3, ),
        /* 36 */ array(7, ),
        /* 37 */ array(24, ),
        /* 38 */ array(8, 9, ),
        /* 39 */ array(8, 9, ),
        /* 40 */ array(2, 5, ),
        /* 41 */ array(6, ),
        /* 42 */ array(7, ),
        /* 43 */ array(17, ),
        /* 44 */ array(5, ),
        /* 45 */ array(17, ),
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
        /* 117 */ array(),
        /* 118 */ array(),
        /* 119 */ array(),
        /* 120 */ array(),
        /* 121 */ array(),
        /* 122 */ array(),
        /* 123 */ array(),
);
    static public $yy_default = array(
 /*     0 */   217,  154,  217,  217,  217,  217,  217,  217,  217,  217,
 /*    10 */   217,  217,  217,  217,  148,  147,  217,  217,  132,  145,
 /*    20 */   146,  217,  217,  132,  217,  131,  217,  144,  217,  143,
 /*    30 */   217,  149,  157,  129,  150,  129,  217,  136,  217,  217,
 /*    40 */   217,  217,  217,  217,  217,  169,  191,  188,  189,  190,
 /*    50 */   179,  178,  177,  134,  192,  180,  186,  185,  187,  156,
 /*    60 */   163,  162,  133,  164,  130,  155,  161,  160,  181,  135,
 /*    70 */   127,  158,  159,  171,  208,  168,  174,  167,  166,  165,
 /*    80 */   175,  152,  173,  170,  194,  193,  153,  151,  137,  128,
 /*    90 */   126,  125,  138,  139,  142,  182,  141,  140,  172,  195,
 /*   100 */   211,  212,  210,  209,  124,  213,  214,  183,  176,  216,
 /*   110 */   215,  207,  206,  199,  198,  197,  196,  200,  201,  205,
 /*   120 */   204,  203,  202,  184,
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
    const YYNOCODE = 93;
    const YYSTACKDEPTH = 100;
    const YYNSTATE = 124;
    const YYNRULE = 93;
    const YYERRORSYMBOL = 56;
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
  'PAR_CLOSE',     'COMA',          'INTERVAL',      'F_SECOND',    
  'F_MINUTE',      'F_HOUR',        'F_DAY',         'F_MONTH',     
  'F_YEAR',        'DOT',           'VARNAME',       'NAME',        
  'NUMVAL',        'STRVAL',        'NOT_EQ',        'LOG_AND',     
  'LOG_OR',        'MATH_DIV',      'MATH_MULT',     'MATH_PLUS',   
  'MATH_MINUS',    'GT',            'LT',            'GE',          
  'LE',            'LIKE',          'NOT_LIKE',      'IN',          
  'NOT_IN',        'F_IF',          'F_ELT',         'F_COALESCE',  
  'F_CONCAT',      'F_SUBSTR',      'F_TRIM',        'F_DATE',      
  'F_DATE_FORMAT',  'F_CURRENT_DATE',  'F_NOW',         'F_TIME',      
  'F_TO_DAYS',     'F_FROM_DAYS',   'F_DATE_ADD',    'F_DATE_SUB',  
  'F_ROUND',       'F_FLOOR',       'F_INET_ATON',   'F_INET_NTOA', 
  'error',         'result',        'query',         'condition',   
  'class_name',    'join_statement',  'where_statement',  'join_item',   
  'join_condition',  'field_id',      'expression_prio4',  'expression_basic',
  'scalar',        'var_name',      'func_name',     'arg_list',    
  'list_operator',  'list',          'expression_prio1',  'operator1',   
  'expression_prio2',  'operator2',     'expression_prio3',  'operator3',   
  'operator4',     'scalar_list',   'argument',      'interval_unit',
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
 /*  15 */ "expression_basic ::= var_name",
 /*  16 */ "expression_basic ::= func_name PAR_OPEN arg_list PAR_CLOSE",
 /*  17 */ "expression_basic ::= PAR_OPEN expression_prio4 PAR_CLOSE",
 /*  18 */ "expression_basic ::= expression_basic list_operator list",
 /*  19 */ "expression_prio1 ::= expression_basic",
 /*  20 */ "expression_prio1 ::= expression_prio1 operator1 expression_basic",
 /*  21 */ "expression_prio2 ::= expression_prio1",
 /*  22 */ "expression_prio2 ::= expression_prio2 operator2 expression_prio1",
 /*  23 */ "expression_prio3 ::= expression_prio2",
 /*  24 */ "expression_prio3 ::= expression_prio3 operator3 expression_prio2",
 /*  25 */ "expression_prio4 ::= expression_prio3",
 /*  26 */ "expression_prio4 ::= expression_prio4 operator4 expression_prio3",
 /*  27 */ "list ::= PAR_OPEN scalar_list PAR_CLOSE",
 /*  28 */ "scalar_list ::= scalar",
 /*  29 */ "scalar_list ::= scalar_list COMA scalar",
 /*  30 */ "arg_list ::=",
 /*  31 */ "arg_list ::= argument",
 /*  32 */ "arg_list ::= arg_list COMA argument",
 /*  33 */ "argument ::= expression_prio4",
 /*  34 */ "argument ::= INTERVAL expression_prio4 interval_unit",
 /*  35 */ "interval_unit ::= F_SECOND",
 /*  36 */ "interval_unit ::= F_MINUTE",
 /*  37 */ "interval_unit ::= F_HOUR",
 /*  38 */ "interval_unit ::= F_DAY",
 /*  39 */ "interval_unit ::= F_MONTH",
 /*  40 */ "interval_unit ::= F_YEAR",
 /*  41 */ "scalar ::= num_scalar",
 /*  42 */ "scalar ::= str_scalar",
 /*  43 */ "num_scalar ::= num_value",
 /*  44 */ "str_scalar ::= str_value",
 /*  45 */ "field_id ::= name",
 /*  46 */ "field_id ::= class_name DOT name",
 /*  47 */ "class_name ::= name",
 /*  48 */ "var_name ::= VARNAME",
 /*  49 */ "name ::= NAME",
 /*  50 */ "num_value ::= NUMVAL",
 /*  51 */ "str_value ::= STRVAL",
 /*  52 */ "operator1 ::= num_operator1",
 /*  53 */ "operator2 ::= num_operator2",
 /*  54 */ "operator2 ::= str_operator",
 /*  55 */ "operator2 ::= EQ",
 /*  56 */ "operator2 ::= NOT_EQ",
 /*  57 */ "operator3 ::= LOG_AND",
 /*  58 */ "operator4 ::= LOG_OR",
 /*  59 */ "num_operator1 ::= MATH_DIV",
 /*  60 */ "num_operator1 ::= MATH_MULT",
 /*  61 */ "num_operator2 ::= MATH_PLUS",
 /*  62 */ "num_operator2 ::= MATH_MINUS",
 /*  63 */ "num_operator2 ::= GT",
 /*  64 */ "num_operator2 ::= LT",
 /*  65 */ "num_operator2 ::= GE",
 /*  66 */ "num_operator2 ::= LE",
 /*  67 */ "str_operator ::= LIKE",
 /*  68 */ "str_operator ::= NOT_LIKE",
 /*  69 */ "list_operator ::= IN",
 /*  70 */ "list_operator ::= NOT_IN",
 /*  71 */ "func_name ::= F_IF",
 /*  72 */ "func_name ::= F_ELT",
 /*  73 */ "func_name ::= F_COALESCE",
 /*  74 */ "func_name ::= F_CONCAT",
 /*  75 */ "func_name ::= F_SUBSTR",
 /*  76 */ "func_name ::= F_TRIM",
 /*  77 */ "func_name ::= F_DATE",
 /*  78 */ "func_name ::= F_DATE_FORMAT",
 /*  79 */ "func_name ::= F_CURRENT_DATE",
 /*  80 */ "func_name ::= F_NOW",
 /*  81 */ "func_name ::= F_TIME",
 /*  82 */ "func_name ::= F_TO_DAYS",
 /*  83 */ "func_name ::= F_FROM_DAYS",
 /*  84 */ "func_name ::= F_YEAR",
 /*  85 */ "func_name ::= F_MONTH",
 /*  86 */ "func_name ::= F_DAY",
 /*  87 */ "func_name ::= F_DATE_ADD",
 /*  88 */ "func_name ::= F_DATE_SUB",
 /*  89 */ "func_name ::= F_ROUND",
 /*  90 */ "func_name ::= F_FLOOR",
 /*  91 */ "func_name ::= F_INET_ATON",
 /*  92 */ "func_name ::= F_INET_NTOA",
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
  array( 'lhs' => 57, 'rhs' => 1 ),
  array( 'lhs' => 57, 'rhs' => 1 ),
  array( 'lhs' => 58, 'rhs' => 4 ),
  array( 'lhs' => 58, 'rhs' => 6 ),
  array( 'lhs' => 62, 'rhs' => 2 ),
  array( 'lhs' => 62, 'rhs' => 0 ),
  array( 'lhs' => 61, 'rhs' => 2 ),
  array( 'lhs' => 61, 'rhs' => 1 ),
  array( 'lhs' => 61, 'rhs' => 0 ),
  array( 'lhs' => 63, 'rhs' => 6 ),
  array( 'lhs' => 63, 'rhs' => 4 ),
  array( 'lhs' => 64, 'rhs' => 3 ),
  array( 'lhs' => 59, 'rhs' => 1 ),
  array( 'lhs' => 67, 'rhs' => 1 ),
  array( 'lhs' => 67, 'rhs' => 1 ),
  array( 'lhs' => 67, 'rhs' => 1 ),
  array( 'lhs' => 67, 'rhs' => 4 ),
  array( 'lhs' => 67, 'rhs' => 3 ),
  array( 'lhs' => 67, 'rhs' => 3 ),
  array( 'lhs' => 74, 'rhs' => 1 ),
  array( 'lhs' => 74, 'rhs' => 3 ),
  array( 'lhs' => 76, 'rhs' => 1 ),
  array( 'lhs' => 76, 'rhs' => 3 ),
  array( 'lhs' => 78, 'rhs' => 1 ),
  array( 'lhs' => 78, 'rhs' => 3 ),
  array( 'lhs' => 66, 'rhs' => 1 ),
  array( 'lhs' => 66, 'rhs' => 3 ),
  array( 'lhs' => 73, 'rhs' => 3 ),
  array( 'lhs' => 81, 'rhs' => 1 ),
  array( 'lhs' => 81, 'rhs' => 3 ),
  array( 'lhs' => 71, 'rhs' => 0 ),
  array( 'lhs' => 71, 'rhs' => 1 ),
  array( 'lhs' => 71, 'rhs' => 3 ),
  array( 'lhs' => 82, 'rhs' => 1 ),
  array( 'lhs' => 82, 'rhs' => 3 ),
  array( 'lhs' => 83, 'rhs' => 1 ),
  array( 'lhs' => 83, 'rhs' => 1 ),
  array( 'lhs' => 83, 'rhs' => 1 ),
  array( 'lhs' => 83, 'rhs' => 1 ),
  array( 'lhs' => 83, 'rhs' => 1 ),
  array( 'lhs' => 83, 'rhs' => 1 ),
  array( 'lhs' => 68, 'rhs' => 1 ),
  array( 'lhs' => 68, 'rhs' => 1 ),
  array( 'lhs' => 84, 'rhs' => 1 ),
  array( 'lhs' => 85, 'rhs' => 1 ),
  array( 'lhs' => 65, 'rhs' => 1 ),
  array( 'lhs' => 65, 'rhs' => 3 ),
  array( 'lhs' => 60, 'rhs' => 1 ),
  array( 'lhs' => 69, 'rhs' => 1 ),
  array( 'lhs' => 88, 'rhs' => 1 ),
  array( 'lhs' => 86, 'rhs' => 1 ),
  array( 'lhs' => 87, 'rhs' => 1 ),
  array( 'lhs' => 75, 'rhs' => 1 ),
  array( 'lhs' => 77, 'rhs' => 1 ),
  array( 'lhs' => 77, 'rhs' => 1 ),
  array( 'lhs' => 77, 'rhs' => 1 ),
  array( 'lhs' => 77, 'rhs' => 1 ),
  array( 'lhs' => 79, 'rhs' => 1 ),
  array( 'lhs' => 80, 'rhs' => 1 ),
  array( 'lhs' => 89, 'rhs' => 1 ),
  array( 'lhs' => 89, 'rhs' => 1 ),
  array( 'lhs' => 90, 'rhs' => 1 ),
  array( 'lhs' => 90, 'rhs' => 1 ),
  array( 'lhs' => 90, 'rhs' => 1 ),
  array( 'lhs' => 90, 'rhs' => 1 ),
  array( 'lhs' => 90, 'rhs' => 1 ),
  array( 'lhs' => 90, 'rhs' => 1 ),
  array( 'lhs' => 91, 'rhs' => 1 ),
  array( 'lhs' => 91, 'rhs' => 1 ),
  array( 'lhs' => 72, 'rhs' => 1 ),
  array( 'lhs' => 72, 'rhs' => 1 ),
  array( 'lhs' => 70, 'rhs' => 1 ),
  array( 'lhs' => 70, 'rhs' => 1 ),
  array( 'lhs' => 70, 'rhs' => 1 ),
  array( 'lhs' => 70, 'rhs' => 1 ),
  array( 'lhs' => 70, 'rhs' => 1 ),
  array( 'lhs' => 70, 'rhs' => 1 ),
  array( 'lhs' => 70, 'rhs' => 1 ),
  array( 'lhs' => 70, 'rhs' => 1 ),
  array( 'lhs' => 70, 'rhs' => 1 ),
  array( 'lhs' => 70, 'rhs' => 1 ),
  array( 'lhs' => 70, 'rhs' => 1 ),
  array( 'lhs' => 70, 'rhs' => 1 ),
  array( 'lhs' => 70, 'rhs' => 1 ),
  array( 'lhs' => 70, 'rhs' => 1 ),
  array( 'lhs' => 70, 'rhs' => 1 ),
  array( 'lhs' => 70, 'rhs' => 1 ),
  array( 'lhs' => 70, 'rhs' => 1 ),
  array( 'lhs' => 70, 'rhs' => 1 ),
  array( 'lhs' => 70, 'rhs' => 1 ),
  array( 'lhs' => 70, 'rhs' => 1 ),
  array( 'lhs' => 70, 'rhs' => 1 ),
  array( 'lhs' => 70, 'rhs' => 1 ),
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
        15 => 12,
        19 => 12,
        21 => 12,
        23 => 12,
        25 => 12,
        33 => 12,
        35 => 12,
        36 => 12,
        37 => 12,
        38 => 12,
        39 => 12,
        40 => 12,
        41 => 12,
        42 => 12,
        16 => 16,
        17 => 17,
        18 => 18,
        20 => 18,
        22 => 18,
        24 => 18,
        26 => 18,
        27 => 27,
        28 => 28,
        31 => 28,
        29 => 29,
        32 => 29,
        30 => 30,
        34 => 34,
        43 => 43,
        44 => 43,
        45 => 45,
        46 => 46,
        47 => 47,
        71 => 47,
        72 => 47,
        73 => 47,
        74 => 47,
        75 => 47,
        76 => 47,
        77 => 47,
        78 => 47,
        79 => 47,
        80 => 47,
        81 => 47,
        82 => 47,
        83 => 47,
        84 => 47,
        85 => 47,
        86 => 47,
        87 => 47,
        88 => 47,
        89 => 47,
        90 => 47,
        91 => 47,
        92 => 47,
        48 => 48,
        49 => 49,
        50 => 50,
        52 => 50,
        53 => 50,
        54 => 50,
        55 => 50,
        56 => 50,
        57 => 50,
        58 => 50,
        59 => 50,
        60 => 50,
        61 => 50,
        62 => 50,
        63 => 50,
        64 => 50,
        65 => 50,
        66 => 50,
        67 => 50,
        68 => 50,
        69 => 50,
        70 => 50,
        51 => 51,
    );
    /* Beginning here are the reduction cases.  A typical example
    ** follows:
    **  #line <lineno> <grammarfile>
    **   function yy_r0($yymsp){ ... }           // User supplied code
    **  #line <lineno> <thisfile>
    */
#line 29 "oql-parser.y"
    function yy_r0(){ $this->my_result = $this->yystack[$this->yyidx + 0]->minor;     }
#line 1270 "oql-parser.php"
#line 32 "oql-parser.y"
    function yy_r2(){
	$this->_retvalue = new OqlObjectQuery($this->yystack[$this->yyidx + -2]->minor, $this->yystack[$this->yyidx + -2]->minor, $this->yystack[$this->yyidx + 0]->minor, $this->yystack[$this->yyidx + -1]->minor);
    }
#line 1275 "oql-parser.php"
#line 35 "oql-parser.y"
    function yy_r3(){
	$this->_retvalue = new OqlObjectQuery($this->yystack[$this->yyidx + -4]->minor, $this->yystack[$this->yyidx + -2]->minor, $this->yystack[$this->yyidx + 0]->minor, $this->yystack[$this->yyidx + -1]->minor);
    }
#line 1280 "oql-parser.php"
#line 48 "oql-parser.y"
    function yy_r4(){ $this->_retvalue = $this->yystack[$this->yyidx + 0]->minor;    }
#line 1283 "oql-parser.php"
#line 49 "oql-parser.y"
    function yy_r5(){ $this->_retvalue = null;    }
#line 1286 "oql-parser.php"
#line 51 "oql-parser.y"
    function yy_r6(){
	// insert the join statement on top of the existing list
	array_unshift($this->yystack[$this->yyidx + 0]->minor, $this->yystack[$this->yyidx + -1]->minor);
	// and return the updated array
	$this->_retvalue = $this->yystack[$this->yyidx + 0]->minor;
    }
#line 1294 "oql-parser.php"
#line 57 "oql-parser.y"
    function yy_r7(){
	$this->_retvalue = Array($this->yystack[$this->yyidx + 0]->minor);
    }
#line 1299 "oql-parser.php"
#line 63 "oql-parser.y"
    function yy_r9(){
	// create an array with one single item
	$this->_retvalue = new OqlJoinSpec($this->yystack[$this->yyidx + -4]->minor, $this->yystack[$this->yyidx + -2]->minor, $this->yystack[$this->yyidx + 0]->minor);
    }
#line 1305 "oql-parser.php"
#line 68 "oql-parser.y"
    function yy_r10(){
	// create an array with one single item
	$this->_retvalue = new OqlJoinSpec($this->yystack[$this->yyidx + -2]->minor, $this->yystack[$this->yyidx + -2]->minor, $this->yystack[$this->yyidx + 0]->minor);
    }
#line 1311 "oql-parser.php"
#line 73 "oql-parser.y"
    function yy_r11(){ $this->_retvalue = new BinaryOqlExpression($this->yystack[$this->yyidx + -2]->minor, '=', $this->yystack[$this->yyidx + 0]->minor);     }
#line 1314 "oql-parser.php"
#line 75 "oql-parser.y"
    function yy_r12(){ $this->_retvalue = $this->yystack[$this->yyidx + 0]->minor;     }
#line 1317 "oql-parser.php"
#line 80 "oql-parser.y"
    function yy_r16(){ $this->_retvalue = new FunctionOqlExpression($this->yystack[$this->yyidx + -3]->minor, $this->yystack[$this->yyidx + -1]->minor);     }
#line 1320 "oql-parser.php"
#line 81 "oql-parser.y"
    function yy_r17(){ $this->_retvalue = $this->yystack[$this->yyidx + -1]->minor;     }
#line 1323 "oql-parser.php"
#line 82 "oql-parser.y"
    function yy_r18(){ $this->_retvalue = new BinaryOqlExpression($this->yystack[$this->yyidx + -2]->minor, $this->yystack[$this->yyidx + -1]->minor, $this->yystack[$this->yyidx + 0]->minor);     }
#line 1326 "oql-parser.php"
#line 97 "oql-parser.y"
    function yy_r27(){
	$this->_retvalue = new ListOqlExpression($this->yystack[$this->yyidx + -1]->minor);
    }
#line 1331 "oql-parser.php"
#line 100 "oql-parser.y"
    function yy_r28(){
	$this->_retvalue = array($this->yystack[$this->yyidx + 0]->minor);
    }
#line 1336 "oql-parser.php"
#line 103 "oql-parser.y"
    function yy_r29(){
	array_push($this->yystack[$this->yyidx + -2]->minor, $this->yystack[$this->yyidx + 0]->minor);
	$this->_retvalue = $this->yystack[$this->yyidx + -2]->minor;
    }
#line 1342 "oql-parser.php"
#line 108 "oql-parser.y"
    function yy_r30(){
	$this->_retvalue = array();
    }
#line 1347 "oql-parser.php"
#line 119 "oql-parser.y"
    function yy_r34(){ $this->_retvalue = new IntervalOqlExpression($this->yystack[$this->yyidx + -1]->minor, $this->yystack[$this->yyidx + 0]->minor);     }
#line 1350 "oql-parser.php"
#line 131 "oql-parser.y"
    function yy_r43(){ $this->_retvalue = new ScalarOqlExpression($this->yystack[$this->yyidx + 0]->minor);     }
#line 1353 "oql-parser.php"
#line 134 "oql-parser.y"
    function yy_r45(){ $this->_retvalue = new FieldOqlExpression($this->yystack[$this->yyidx + 0]->minor);     }
#line 1356 "oql-parser.php"
#line 135 "oql-parser.y"
    function yy_r46(){ $this->_retvalue = new FieldOqlExpression($this->yystack[$this->yyidx + 0]->minor, $this->yystack[$this->yyidx + -2]->minor);     }
#line 1359 "oql-parser.php"
#line 136 "oql-parser.y"
    function yy_r47(){ $this->_retvalue=$this->yystack[$this->yyidx + 0]->minor;     }
#line 1362 "oql-parser.php"
#line 139 "oql-parser.y"
    function yy_r48(){ $this->_retvalue = new VariableOqlExpression(substr($this->yystack[$this->yyidx + 0]->minor, 1));     }
#line 1365 "oql-parser.php"
#line 141 "oql-parser.y"
    function yy_r49(){
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
#line 1378 "oql-parser.php"
#line 153 "oql-parser.y"
    function yy_r50(){$this->_retvalue=$this->yystack[$this->yyidx + 0]->minor;    }
#line 1381 "oql-parser.php"
#line 154 "oql-parser.y"
    function yy_r51(){$this->_retvalue=stripslashes(substr($this->yystack[$this->yyidx + 0]->minor, 1, strlen($this->yystack[$this->yyidx + 0]->minor) - 2));    }
#line 1384 "oql-parser.php"

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
#line 1500 "oql-parser.php"
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
}#line 204 "oql-parser.y"


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

#line 1719 "oql-parser.php"

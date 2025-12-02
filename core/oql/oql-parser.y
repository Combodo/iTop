
/*

This is a LALR(1) grammar
(seek for Lemon grammar to get some documentation from the Net)
That doc was helpful: http://www.hwaci.com/sw/lemon/lemon.html

To handle operators precedence we could have used the %left directive
(we took another option, because that one was discovered right after...
which option is the best for us?)
Example:
%left LOG_AND.
%left LOG_OR.
%nonassoc EQ NE GT GE LT LE.
%left PLUS MINUS.
%left TIMES DIVIDE MOD.
%right EXP NOT.

later : solve the 2 remaining shift-reduce conflicts (JOIN)

*/

%name OQLParser_
%declare_class {class OQLParserRaw}
%syntax_error { 
throw new OQLParserSyntaxErrorException($this->m_sSourceQuery, $this->m_iLine, $this->m_iCol, $this->tokenName($yymajor), $TOKEN);
}
/* Bug N°4052 Parser stack size too small for huge OQL requests */
%stack_size 1000
%stack_overflow {
throw new OQLParserStackOverFlowException($this->m_sSourceQuery, $this->m_iLine, $this->m_iCol);
}
%parse_failure {
throw new OQLParserParseFailureException($this->m_sSourceQuery, $this->m_iLine, $this->m_iCol);
}

result ::= union(X). { $this->my_result = X; }
result ::= query(X). { $this->my_result = X; }
result ::= condition(X). { $this->my_result = X; }

union(A) ::= query(X) UNION query(Y). {
	A = new OqlUnionQuery(X, Y);
}
union(A) ::= query(X) UNION union(Y). {
	A = new OqlUnionQuery(X, Y);
}

query(A) ::= SELECT class_name(X) join_statement(J) where_statement(W). {
	A = new OqlObjectQuery(X, X, W, J, array(X));
}
query(A) ::= SELECT class_name(X) AS_ALIAS class_name(Y) join_statement(J) where_statement(W). {
	A = new OqlObjectQuery(X, Y, W, J, array(Y));
}

query(A) ::= SELECT class_list(E) FROM class_name(X) join_statement(J) where_statement(W). {
	A = new OqlObjectQuery(X, X, W, J, E);
}
query(A) ::= SELECT class_list(E) FROM class_name(X) AS_ALIAS class_name(Y) join_statement(J) where_statement(W). {
	A = new OqlObjectQuery(X, Y, W, J, E);
}


class_list(A) ::= class_name(X). {
	A = array(X);
}
class_list(A) ::= class_list(L) COMA class_name(X). {
	array_push(L, X);
	A = L;
}

where_statement(A) ::= WHERE condition(C). { A = C;}
where_statement(A) ::= . { A = null;}

join_statement(A) ::= join_statement(S) JOIN join_item(J). {
	// insert the join statement on top of the existing list
	array_push(S, J);
	// and return the updated array
	A = S;
}
join_statement(A) ::= . { A = [];}

join_item(A) ::= class_name(X) AS_ALIAS class_name(Y) ON join_condition(C).
{
	// create an array with one single item
	A = new OqlJoinSpec(X, Y, C);
}
join_item(A) ::= class_name(X) ON join_condition(C).
{
	// create an array with one single item
	A = new OqlJoinSpec(X, X, C);
}
join_condition(A) ::= field_id(X) EQ field_id(Y). { A = new BinaryOqlExpression(X, '=', Y); }
join_condition(A) ::= field_id(X) BELOW field_id(Y). { A = new BinaryOqlExpression(X, 'BELOW', Y); }
join_condition(A) ::= field_id(X) BELOW_STRICT field_id(Y). { A = new BinaryOqlExpression(X, 'BELOW_STRICT', Y); }
join_condition(A) ::= field_id(X) NOT_BELOW field_id(Y). { A = new BinaryOqlExpression(X, 'NOT_BELOW', Y); }
join_condition(A) ::= field_id(X) NOT_BELOW_STRICT field_id(Y). { A = new BinaryOqlExpression(X, 'NOT_BELOW_STRICT', Y); }
join_condition(A) ::= field_id(X) ABOVE field_id(Y). { A = new BinaryOqlExpression(X, 'ABOVE', Y); }
join_condition(A) ::= field_id(X) ABOVE_STRICT field_id(Y). { A = new BinaryOqlExpression(X, 'ABOVE_STRICT', Y); }
join_condition(A) ::= field_id(X) NOT_ABOVE field_id(Y). { A = new BinaryOqlExpression(X, 'NOT_ABOVE', Y); }
join_condition(A) ::= field_id(X) NOT_ABOVE_STRICT field_id(Y). { A = new BinaryOqlExpression(X, 'NOT_ABOVE_STRICT', Y); }

condition(A) ::= expression_prio4(X). { A = X; }

expression_basic(A) ::= scalar(X). { A = X; } 
expression_basic(A) ::= field_id(X). { A = X; }
expression_basic(A) ::= var_name(X). { A = X; }
expression_basic(A) ::= func_name(X) PAR_OPEN arg_list(Y) PAR_CLOSE. { A = new FunctionOqlExpression(X, Y); }
expression_basic(A) ::= PAR_OPEN expression_prio4(X) PAR_CLOSE. { A = X; }
expression_basic(A) ::= expression_basic(X) list_operator(Y) list(Z). { A = new BinaryOqlExpression(X, Y, Z); }

expression_prio1(A) ::= expression_basic(X). { A = X; }
expression_prio1(A) ::= expression_prio1(X) operator1(Y) expression_basic(Z). { A = new BinaryOqlExpression(X, Y, Z); }

expression_prio2(A) ::= expression_prio1(X). { A = X; }
expression_prio2(A) ::= expression_prio2(X) operator2(Y) expression_prio1(Z).{
    if (Y == 'MATCHES')
    {
        A = new MatchOqlExpression(X, Z);
    }
    else
    {
        A = new BinaryOqlExpression(X, Y, Z);
    }
}

expression_prio3(A) ::= expression_prio2(X). { A = X; }
expression_prio3(A) ::= expression_prio3(X) operator3(Y) expression_prio2(Z). { A = new BinaryOqlExpression(X, Y, Z); }

expression_prio4(A) ::= expression_prio3(X). { A = X; }
expression_prio4(A) ::= expression_prio4(X) operator4(Y) expression_prio3(Z). { A = new BinaryOqlExpression(X, Y, Z); }

list(A) ::= PAR_OPEN list_items(X) PAR_CLOSE. {
	A = new ListOqlExpression(X);
}
list(A) ::= PAR_OPEN query(X) PAR_CLOSE. {
	A = new NestedQueryOqlExpression(X);
}
list(A) ::= PAR_OPEN union(X) PAR_CLOSE. {
	A = new NestedQueryOqlExpression(X);
}

list_items(A) ::= expression_prio4(X). {
	A = array(X);
}
list_items(A) ::= list_items(L) COMA expression_prio4(X). {
	array_push(L, X);
	A = L;
}

arg_list(A) ::= . {
	A = array();
}
arg_list(A) ::= argument(X). {
	A = array(X);
}
arg_list(A) ::= arg_list(L) COMA argument(X). {
	array_push(L, X);
	A = L;
}
argument(A) ::= expression_prio4(X). { A = X; }
argument(A) ::= INTERVAL expression_prio4(X) interval_unit(Y). { A = new IntervalOqlExpression(X, Y); }

interval_unit(A) ::= F_SECOND(X). { A = X; }
interval_unit(A) ::= F_MINUTE(X). { A = X; }
interval_unit(A) ::= F_HOUR(X). { A = X; }
interval_unit(A) ::= F_DAY(X). { A = X; }
interval_unit(A) ::= F_MONTH(X). { A = X; }
interval_unit(A) ::= F_YEAR(X). { A = X; }

scalar(A) ::= num_scalar(X). { A = X; }
scalar(A) ::= str_scalar(X). { A = X; }
scalar(A) ::= null_scalar(X). { A = X; }

num_scalar(A) ::= num_value(X). { A = new ScalarOqlExpression(X); }
str_scalar(A) ::= str_value(X). { A = new ScalarOqlExpression(X); }
null_scalar(A) ::= NULL_VAL. { A = new ScalarOqlExpression(null); }

field_id(A) ::= name(X). { A = new FieldOqlExpression(X); }
field_id(A) ::= class_name(X) DOT name(Y). { A = new FieldOqlExpression(Y, X); }
class_name(A) ::= name(X). { A=X; }


var_name(A) ::= VARNAME(X). { A = new VariableOqlExpression(substr(X, 1)); }

name(A) ::= NAME(X). {
	if (X[0] == '`')
	{
		$name = substr(X, 1, strlen(X) - 2);
	}
	else
	{
		$name = X;
	}
	A = new OqlName($name, $this->m_iColPrev);
}
num_value(A) ::= NUMVAL(X). {A=(int)X;}
num_value(A) ::= MATH_MINUS NUMVAL(X). {A=(int)-X;}
num_value(A) ::= HEXVAL(X). {A=new OqlHexValue(X);}
str_value(A) ::= STRVAL(X). {A=stripslashes(substr(X, 1, strlen(X) - 2));}


operator1(A) ::= num_operator1(X). {A=X;}
operator1(A) ::= bitwise_operator1(X). {A=X;}

operator2(A) ::= num_operator2(X). {A=X;}
operator2(A) ::= str_operator(X). {A=X;}
operator2(A) ::= REGEXP(X). {A=X;}
operator2(A) ::= EQ(X). {A=X;}
operator2(A) ::= NOT_EQ(X). {A=X;}

operator3(A) ::= LOG_AND(X). {A=X;}
operator3(A) ::= bitwise_operator3(X). {A=X;}

operator4(A) ::= LOG_OR(X). {A=X;}
operator4(A) ::= bitwise_operator4(X). {A=X;}

num_operator1(A) ::= MATH_DIV(X). {A=X;}
num_operator1(A) ::= MATH_MULT(X). {A=X;}

num_operator2(A) ::= MATH_PLUS(X). {A=X;}
num_operator2(A) ::= MATH_MINUS(X). {A=X;}
num_operator2(A) ::= GT(X). {A=X;}
num_operator2(A) ::= LT(X). {A=X;}
num_operator2(A) ::= GE(X). {A=X;}
num_operator2(A) ::= LE(X). {A=X;}

str_operator(A) ::= LIKE(X). {A=X;}
str_operator(A) ::= NOT_LIKE(X). {A=X;}
str_operator(A) ::= MATCHES(X). {A=X;}

bitwise_operator1(A) ::= BITWISE_LEFT_SHIFT(X). {A=X;}
bitwise_operator1(A) ::= BITWISE_RIGHT_SHIFT(X). {A=X;}

bitwise_operator3(A) ::= BITWISE_AND(X). {A=X;}

bitwise_operator4(A) ::= BITWISE_OR(X). {A=X;}
bitwise_operator4(A) ::= BITWISE_XOR(X). {A=X;}

list_operator(A) ::= IN(X). {A=X;}
list_operator(A) ::= NOT_IN(X). {A=X;}

func_name(A) ::= F_IF(X). { A=X; }
func_name(A) ::= F_ELT(X). { A=X; }
func_name(A) ::= F_COALESCE(X). { A=X; }
func_name(A) ::= F_ISNULL(X). { A=X; }
func_name(A) ::= F_CONCAT(X). { A=X; }
func_name(A) ::= F_SUBSTR(X). { A=X; }
func_name(A) ::= F_TRIM(X). { A=X; }
func_name(A) ::= F_DATE(X). { A=X; }
func_name(A) ::= F_DATE_FORMAT(X). { A=X; }
func_name(A) ::= F_CURRENT_DATE(X). { A=X; }
func_name(A) ::= F_NOW(X). { A=X; }
func_name(A) ::= F_TIME(X). { A=X; }
func_name(A) ::= F_TO_DAYS(X). { A=X; }
func_name(A) ::= F_FROM_DAYS(X). { A=X; }
func_name(A) ::= F_YEAR(X). { A=X; }
func_name(A) ::= F_MONTH(X). { A=X; }
func_name(A) ::= F_DAY(X). { A=X; }
func_name(A) ::= F_DATE_ADD(X). { A=X; }
func_name(A) ::= F_DATE_SUB(X). { A=X; }
func_name(A) ::= F_ROUND(X). { A=X; }
func_name(A) ::= F_FLOOR(X). { A=X; }
func_name(A) ::= F_INET_ATON(X). { A=X; }
func_name(A) ::= F_INET_NTOA(X). { A=X; }


%code {

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

}

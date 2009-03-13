%name OQLParser_
%declare_class {class OQLParserRaw}
%syntax_error { 
throw new OQLParserException($this->m_sSourceQuery, $this->m_iLine, $this->m_iCol, $this->tokenName($yymajor), $TOKEN);
}

result ::= query(X). { $this->my_result = X; }
result ::= condition(X). { $this->my_result = X; }

query(A) ::= SELECT class_name(X) join_statement(J) where_statement(W). {
	A = new OqlQuery(X, X, W, J);
}
query(A) ::= SELECT class_name(X) AS_ALIAS class_name(Y) join_statement(J) where_statement(W). {
	A = new OqlQuery(X, Y, W, J);
}

where_statement(A) ::= WHERE condition(C). { A = C;}
where_statement(A) ::= . { A = null;}

join_statement(A) ::= join_item(J) join_statement(S). {
	// insert the join statement on top of the existing list
	array_unshift(S, J);
	// and return the updated array
	A = S;
}
join_statement(A) ::= join_item(J). {
	A = Array(J);
}
join_statement(A) ::= . { A = null;}

join_item(A) ::= JOIN class_name(X) AS_ALIAS class_name(Y) ON join_condition(C).
{
	// create an array with one single item
	A = new OqlJoinSpec(X, Y, C);
}
join_item(A) ::= JOIN class_name(X) ON join_condition(C).
{
	// create an array with one single item
	A = new OqlJoinSpec(X, X, C);
}

join_condition(A) ::= field_id(X) EQ field_id(Y). { A = new BinaryOqlExpression(X, '=', Y); }

condition(A) ::= expression(X). { A = X; }

expression(A) ::= PAR_OPEN expression(X) PAR_CLOSE. { A = X; }
expression(A) ::= expression(X) operator(Y) expression(Z). { A = new BinaryOqlExpression(X, Y, Z); }
expression(A) ::= scalar(X). { A=X; } 
expression(A) ::= field_id(X). { A = X; }
expression(A) ::= expression(X) list_operator(Y) list(Z). { A = new BinaryOqlExpression(X, Y, Z); }
expression(A) ::= func_name(X) PAR_OPEN arg_list(Y) PAR_CLOSE. { A = new FunctionOqlExpression(X, Y); }


list(A) ::= PAR_OPEN scalar_list(X) PAR_CLOSE. {
	A = new ListOqlExpression(X);
}
scalar_list(A) ::= scalar(X). {
	A = array(X);
}
scalar_list(A) ::= scalar_list(L) COMA scalar(X). {
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
argument(A) ::= expression(X). { A = X; }
argument(A) ::= INTERVAL expression(X) interval_unit(Y). { A = new IntervalOqlExpression(X, Y); }

interval_unit(A) ::= F_DAY(X). { A = X; }
interval_unit(A) ::= F_MONTH(X). { A = X; }
interval_unit(A) ::= F_YEAR(X). { A = X; }

scalar(A) ::= num_scalar(X). { A = X; }
scalar(A) ::= str_scalar(X). { A = X; }

num_scalar(A) ::= num_value(X). { A = new ScalarOqlExpression(X); }
str_scalar(A) ::= str_value(X). { A = new ScalarOqlExpression(X); }

field_id(A) ::= class_name(X) DOT name(Y). { A = new FieldOqlExpression($this->m_iCol, Y, X); }
class_name(A) ::= name(X). {A=X;}

name(A) ::= NAME(X). {
	if (X[0] == '`')
	{
		A = substr(X, 1, strlen(X) - 2);
	}
	else
	{
		A = X;
	}
}

num_value(A) ::= NUMVAL(X). {A=X;}
str_value(A) ::= STRVAL(X). {A=stripslashes(substr(X, 1, strlen(X) - 2));}

operator(A) ::= log_operator(X). {A=X;}
operator(A) ::= num_operator(X). {A=X;}
operator(A) ::= str_operator(X). {A=X;}
operator(A) ::= EQ(X). {A=X;}
operator(A) ::= NOT_EQ(X). {A=X;}

log_operator(A) ::= LOG_AND(X). {A=X;}
log_operator(A) ::= LOG_OR(X). {A=X;}

num_operator(A) ::= GT(X). {A=X;}
num_operator(A) ::= LT(X). {A=X;}
num_operator(A) ::= GE(X). {A=X;}
num_operator(A) ::= LE(X). {A=X;}
num_operator(A) ::= MATH_DIV(X). {A=X;}
num_operator(A) ::= MATH_MULT(X). {A=X;}
num_operator(A) ::= MATH_PLUS(X). {A=X;}
num_operator(A) ::= MATH_MINUS(X). {A=X;}

str_operator(A) ::= LIKE(X). {A=X;}
str_operator(A) ::= NOT_LIKE(X). {A=X;}

list_operator(A) ::= IN(X). {A=X;}
list_operator(A) ::= NOT_IN(X). {A=X;}

func_name(A) ::= F_IF(X). { A=X; }
func_name(A) ::= F_ELT(X). { A=X; }
func_name(A) ::= F_COALESCE(X). { A=X; }
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


%code {


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

}

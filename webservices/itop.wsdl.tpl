<?xml version='1.0' encoding='UTF-8'?>

<!-- WSDL file generated manually -->

<definitions name="ITop" targetNamespace="urn:ITop" xmlns:typens="urn:ITop" xmlns:xsd="http://www.w3.org/2001/XMLSchema" xmlns:soap="http://schemas.xmlsoap.org/wsdl/soap/" xmlns:soapenc="http://schemas.xmlsoap.org/soap/encoding/" xmlns:wsdl="http://schemas.xmlsoap.org/wsdl/" xmlns="http://schemas.xmlsoap.org/wsdl/">
	<types>
		<xsd:schema xmlns="http://www.w3.org/2001/XMLSchema" targetNamespace="urn:ITop">
			<!-- Added the following import tag to pass the Eclipse validation -->
			<xsd:import namespace="http://schemas.xmlsoap.org/soap/encoding/" />
			<xsd:complexType name="SearchCondition">
				<!--wsdl:documentation>
					A criteria to restrict a search (strict search is performed)
					Example: name = 'myserver.combodo.fr'
				</wsdl:documentation -->
				<xsd:all>
					<xsd:element name="attcode" type="xsd:string"/>
					<xsd:element name="value" type="xsd:string"/> <!-- should be anyType but this one is not well supported by Eclipse -->
				</xsd:all>
			</xsd:complexType>
			<xsd:complexType name="ArrayOfSearchCondition">
				<xsd:complexContent mixed="false">
					<xsd:restriction base="soapenc:Array">
						<xsd:attribute ref="soapenc:arrayType" wsdl:arrayType="typens:SearchCondition[]"/>
					</xsd:restriction>
				</xsd:complexContent>
			</xsd:complexType>
			<xsd:complexType name="ExternalKeySearch">
				<!-- wsdl:documentation>
					Specifies [how to find] a value for an external key.
					the class of object to search for will depend on the usage that is being made, therefore the search conditions that may be used will vary depending on the parameter that is concerned.
					If one criteria is not relevant, then the match will not be attempted and warning will be logged (or an error if the target external key is mandatory)
					Example: match on customer = 'Demo' and type = 'Router'
				</wsdl:documentation -->
				<xsd:all>
					<xsd:element name="conditions" type="typens:ArrayOfSearchCondition"/>
				</xsd:all>
			</xsd:complexType>
			<xsd:complexType name="AttributeValue">
				<!-- wsdl:documentation>
					Specifies a value to set
				</wsdl:documentation -->
				<xsd:all>
					<xsd:element name="attcode" type="xsd:string"/>
					<xsd:element name="value" type="xsd:string"/> <!-- should be anyType but this one is not well supported by Eclipse -->
				</xsd:all>
			</xsd:complexType>
			<xsd:complexType name="ArrayOfAttributeValue">
				<xsd:complexContent mixed="false">
					<xsd:restriction base="soapenc:Array">
						<xsd:attribute ref="soapenc:arrayType" wsdl:arrayType="typens:AttributeValue[]"/>
					</xsd:restriction>
				</xsd:complexContent>
			</xsd:complexType>
			<xsd:complexType name="LinkCreationSpec">
				<!-- wsdl:documentation>
					Specifies [how to match] one item to attach and what values should be set on the newly created link.
				</wsdl:documentation -->
				<xsd:all>
					<xsd:element name="class" type="xsd:string"/>
					<xsd:element name="conditions" type="typens:ArrayOfSearchCondition"/>
					<xsd:element name="attributes" type="typens:ArrayOfAttributeValue"/>
				</xsd:all>
			</xsd:complexType>
			<xsd:complexType name="ArrayOfLinkCreationSpec">
				<xsd:complexContent mixed="false">
					<xsd:restriction base="soapenc:Array">
						<xsd:attribute ref="soapenc:arrayType" wsdl:arrayType="typens:LinkCreationSpec[]"/>
					</xsd:restriction>
				</xsd:complexContent>
			</xsd:complexType>
			<xsd:complexType name="LogMessage">
				<!-- wsdl:documentation>
					An event that happened during the execution of the web service
				</wsdl:documentation -->
				<xsd:all>
					<xsd:element name="text" type="xsd:string"/>
				</xsd:all>
			</xsd:complexType>
			<xsd:complexType name="ArrayOfLogMessage">
				<xsd:complexContent mixed="false">
					<xsd:restriction base="soapenc:Array">
						<xsd:attribute ref="soapenc:arrayType" wsdl:arrayType="typens:LogMessage[]"/>
					</xsd:restriction>
				</xsd:complexContent>
			</xsd:complexType>
			<xsd:complexType name="ResultLog">
				<!-- wsdl:documentation>
					A Log of events of the same category
				</wsdl:documentation -->
				<xsd:all>
					<xsd:element name="messages" type="typens:ArrayOfLogMessage"/>
				</xsd:all>
			</xsd:complexType>
			<xsd:complexType name="ResultData">
				<xsd:all>
					<xsd:element name="key" type="xsd:string"/>
					<xsd:element name="value" type="xsd:string"/> <!-- should be anyType but this one is not well supported by Eclipse -->
				</xsd:all>
			</xsd:complexType>
			<xsd:complexType name="ArrayOfResultData">
				<xsd:complexContent mixed="false">
					<xsd:restriction base="soapenc:Array">
						<xsd:attribute ref="soapenc:arrayType" wsdl:arrayType="typens:ResultData[]"/>
					</xsd:restriction>
				</xsd:complexContent>
			</xsd:complexType>
			<xsd:complexType name="ResultMessage">
				<!-- wsdl:documentation>
					Output expected, depending on the operation invoked.
					Example: CreateIncidentTicket will return 'created' => basic information on the created ticket
				</wsdl:documentation -->
				<xsd:all>
					<xsd:element name="label" type="xsd:string"/>
					<xsd:element name="values" type="typens:ArrayOfResultData"/>
				</xsd:all>
			</xsd:complexType>
			<xsd:complexType name="ArrayOfResultMessage">
				<xsd:complexContent mixed="false">
					<xsd:restriction base="soapenc:Array">
						<xsd:attribute ref="soapenc:arrayType" wsdl:arrayType="typens:ResultMessage[]"/>
					</xsd:restriction>
				</xsd:complexContent>
			</xsd:complexType>
			<xsd:complexType name="Result">
				<!-- wsdl:documentation>
					Standard result structure returned by all of the operations, excepted GetVersion (returning a string)
					result holds returned data if the status is set to true
					errors, warnings and infos will help in understanding what happened (unknown identifiers, object matching issues/results)
					This resulting structure is being tracked into the application log as well.
				</wsdl:documentation -->
				<xsd:all>
					<xsd:element name="status" type="xsd:boolean"/>
					<xsd:element name="result" type="typens:ArrayOfResultMessage"/>
					<xsd:element name="errors" type="typens:ResultLog"/>
					<xsd:element name="warnings" type="typens:ResultLog"/>
					<xsd:element name="infos" type="typens:ResultLog"/>
				</xsd:all>
			</xsd:complexType>
		</xsd:schema>
	</types>
	<message name="GetVersion">
	</message>
	<message name="GetVersionResponse">
		<part name="GetVersionReturn" type="xsd:string"/>
	</message>
	<message name="CreateRequestTicket">
		<part name="login" type="xsd:string"/>
		<part name="password" type="xsd:string"/>
		<part name="title" type="xsd:string"/>
		<part name="description" type="xsd:string"/>
		<part name="caller" type="typens:ExternalKeySearch"/>
		<part name="customer" type="typens:ExternalKeySearch"/>
		<part name="service" type="typens:ExternalKeySearch"/>
		<part name="service_subcategory" type="typens:ExternalKeySearch"/>
		<part name="product" type="xsd:string"/>
		<part name="workgroup" type="typens:ExternalKeySearch"/>
		<part name="impacted_cis" type="typens:ArrayOfLinkCreationSpec"/>
		<part name="impact" type="xsd:string"/>
		<part name="urgency" type="xsd:string"/>
	</message>
	<message name="CreateIncidentTicket">
		<part name="login" type="xsd:string"/>
		<part name="password" type="xsd:string"/>
		<part name="title" type="xsd:string"/>
		<part name="description" type="xsd:string"/>
		<part name="caller" type="typens:ExternalKeySearch"/>
		<part name="customer" type="typens:ExternalKeySearch"/>
		<part name="service" type="typens:ExternalKeySearch"/>
		<part name="service_subcategory" type="typens:ExternalKeySearch"/>
		<part name="product" type="xsd:string"/>
		<part name="workgroup" type="typens:ExternalKeySearch"/>
		<part name="impacted_cis" type="typens:ArrayOfLinkCreationSpec"/>
		<part name="impact" type="xsd:string"/>
		<part name="urgency" type="xsd:string"/>
	</message>
	<message name="CreateIncidentTicketResponse">
		<part name="CreateIncidentTicketReturn" type="typens:Result"/>
	</message>
	<message name="CreateRequestTicketResponse">
		<part name="CreateRequestTicketReturn" type="typens:Result"/>
	</message>
	<message name="SearchObjects">
		<part name="login" type="xsd:string"/>
		<part name="password" type="xsd:string"/>
		<part name="oql" type="xsd:string"/>
	</message>
	<message name="SearchObjectsResponse">
		<part name="SearchObjectsReturn" type="typens:Result"/>
	</message>
	<portType name="WebServicePortType">
		<operation name="GetVersion">
			<!-- wsdl:documentation>
				Get the current version of Itop
				As this service is very simple, it is a test to get trained for more complex operations 
			</wsdl:documentation --> -->
			<input message="typens:GetVersion"/>
			<output message="typens:GetVersionResponse"/>
		</operation>
		<operation name="CreateRequestTicket">
			<!-- wsdl:documentation>
				Create a ticket, return information about reconciliation on external keys and the created ticket
			</wsdl:documentation -->
			<input message="typens:CreateRequestTicket"/>
			<output message="typens:CreateRequestTicketResponse"/>
		</operation>
		<operation name="CreateIncidentTicket">
			<!-- wsdl:documentation>
				Create a ticket, return information about reconciliation on external keys and the created ticket
			</wsdl:documentation -->
			<input message="typens:CreateIncidentTicket"/>
			<output message="typens:CreateIncidentTicketResponse"/>
		</operation>
		<operation name="SearchObjects">
			<!-- wsdl:documentation>
				Create a ticket, return information about reconciliation on external keys and the created ticket
			</wsdl:documentation -->
			<input message="typens:SearchObjects"/>
			<output message="typens:SearchObjectsResponse"/>
		</operation>
	</portType>
	<binding name="WebServiceBinding" type="typens:WebServicePortType">
		<soap:binding style="rpc" transport="http://schemas.xmlsoap.org/soap/http"/>
		<operation name="GetVersion">
			<soap:operation soapAction="urn:WebServiceAction"/>
			<input>
				<soap:body namespace="urn:ITop" use="encoded" encodingStyle="http://schemas.xmlsoap.org/soap/encoding/"/>
			</input>
			<output>
				<soap:body namespace="urn:ITop" use="encoded" encodingStyle="http://schemas.xmlsoap.org/soap/encoding/"/>
			</output>
		</operation>
		<operation name="CreateRequestTicket">
			<soap:operation soapAction="urn:WebServiceAction"/>
			<input>
				<soap:body namespace="urn:ITop" use="encoded" encodingStyle="http://schemas.xmlsoap.org/soap/encoding/"/>
			</input>
			<output>
				<soap:body namespace="urn:ITop" use="encoded" encodingStyle="http://schemas.xmlsoap.org/soap/encoding/"/>
			</output>
		</operation>
		<operation name="CreateIncidentTicket">
			<soap:operation soapAction="urn:WebServiceAction"/>
			<input>
				<soap:body namespace="urn:ITop" use="encoded" encodingStyle="http://schemas.xmlsoap.org/soap/encoding/"/>
			</input>
			<output>
				<soap:body namespace="urn:ITop" use="encoded" encodingStyle="http://schemas.xmlsoap.org/soap/encoding/"/>
			</output>
		</operation>
		<operation name="SearchObjects">
			<soap:operation soapAction="urn:WebServiceAction"/>
			<input>
				<soap:body namespace="urn:ITop" use="encoded" encodingStyle="http://schemas.xmlsoap.org/soap/encoding/"/>
			</input>
			<output>
				<soap:body namespace="urn:ITop" use="encoded" encodingStyle="http://schemas.xmlsoap.org/soap/encoding/"/>
			</output>
		</operation>
	</binding>
	<service name="ITopService">
		<!-- wsdl:documentation>
			ITop is the central solution for managing your IT infrastructure
		</wsdl:documentation -->
		<port name="WebServicePort" binding="typens:WebServiceBinding">
			<soap:address location="___SOAP_SERVER_URI___"/>
		</port>
	</service>
</definitions>


--- Customization of the portal

This is the way it is working now and is highly subject to change...


Configuration (itop-config.php)
===============================
portal_tickets: CSV value to specify which ticket classes are enabled (default to 'UserRequest') 


Common constants (XML)
======================
PORTAL_POWER_USER_PROFILE: Name of the profile that determines who can see the ticket of her organization (not only the tickets she is caller for)
PORTAL_SERVICECATEGORY_QUERY: OQL to list the services (parameters available: org_id)
PORTAL_SERVICE_SUBCATEGORY_QUERY: OQL to list the service subcategories (parameters available: org_id, svc_id)
PORTAL_VALIDATE_SERVICECATEGORY_QUERY: OQL to check the service again (security against malicious HTTP POSTs)
PORTAL_VALIDATE_SERVICESUBCATEGORY_QUERY: OQL to check the service again (security against malicious HTTP POSTs)
PORTAL_ALL_PARAMS: parameters that the wizard will kindly propagate through its pages (mixing should not be a problem, default value could be cleaned a little...)
PORTAL_SET_TYPE_FROM: attribute of the class ServiceSubcategory determining the request type
PORTAL_TYPE_TO_CLASS: optional mapping from the request types to ticket classes
PORTAL_TICKETS_SEARCH_CRITERIA: comma separated list of search criteria (attcodes) for closed tickets
PORTAL_TICKETS_SEARCH_FILTER_attcode: an OQL query to limit the list of values available in the search form (drop-down list). One define per entry in PORTAL_TICKETS_SEARCH_CRITERIA


Caution: Hardcoded stuff
========================
Classes Service and ServiceSubcategory
A user can update a ticket (new/assigned)
A user can close a ticket (resolved) (user_satisfaction is hardcoded though user_comment is not)


Constants depending on the class of ticket
==========================================
For each ticket class enabled, you will have to define these constants:

PORTAL_<TICKET-CLASS>_PUBLIC_LOG: name of the public log attribute
PORTAL_<TICKET-CLASS>_USER_COMMENT: name of the user comment attribute (legacy, used to be user_commmmment)
PORTAL_<TICKET-CLASS>_FORM_ATTRIBUTES: attributes proposed to the end-user in the edition form
PORTAL_<TICKET-CLASS>_TYPE: optional attribute to be set with the value of "request type"
PORTAL_<TICKET-CLASS>_LIST_ZLIST: list of attribute displayed in the lists (opened and resolved)
PORTAL_<TICKET-CLASS>_CLOSED_ZLIST: list of attribute displayed in the list of closed tickets
PORTAL_<TICKET-CLASS>_DETAILS_ZLIST: selection and presentation of attributes in the page that shows their details
PORTAL_<TICKET-CLASS>_DISPLAY_QUERY: selection of displayable objects (use parameters contact->attcode to check things against the user/contact)
PORTAL_<TICKET-CLASS>_DISPLAY_POWERUSER_QUERY: selection of displayable objects for power users (use parameters contact->attcode to check things against the user/contact)


How to add a type of ticket (example: Incident)
===============================================
1) Add it to the list of supported tickets classes: itop-config.php/portal_tickets
2) Define PORTAL_SET_TYPE_FROM (if not already done) as the attribute of ServiceSubcategory, that will define the request type, depending on the user selection
3) Map the different values of this request type (in class ServiceSubcategory) to the supported ticket classes
YOU MUST MAKE SURE THAT ANY OF THE VALUE HAS A MAPPING SO AS TO EXCLUDE SUBCATEGORIES IF THE CORRESPONDING CLASS ARE NOT ENABLED IN THE CONFIG.
4) Make sure that the queries PORTAL_SERVICE_SUBCATEGORY_QUERY and PORTAL_VALIDATE_SERVICESUBCATEGORY_QUERY will not exclude the expected type
5) Define the various constants for this class (PORTAL_<MY-CLASS>_XXXX).
6) Adjust PORTAL_TICKETS_SEARCH_CRITERIA. Those criteria are common to all types of tickets. Giving too many criteria can lead to confusion.
7) Test, test and re-test!!!


How to copy the request type to the ticket
==========================================
1) Define PORTAL_SET_TYPE_FROM (if not already done) as the attribute of ServiceSubcategory, that will define the request type, depending on the user selection
2) Define PORTAL_<TICKET-CLASS>_TYPE as the tiket attribute code to which the request type will be copied as is. There is no mapping.


Behavior of the lists when handling several types of tickets
============================================================
There are three lists: opened tickets, resolved tickets and closed tickets.
The following explanation applies to any of those lists.
 * If no item has been found, one single message is displayed (no request of this category).
 * If a number of items of only one category have been found, the list is displayed as is.
 * Otherwise, there are several types of tickets to display. Each sub-list is preceeded by the name of the corresponding class.

.. Copyright (C) 2010-2021 Combodo SARL
.. http://opensource.org/licenses/AGPL-3.0

Alert
=====

Alerts are the main component to give feedback to the user or communicate page specific to system wide messages.
Alerts are a rectangular component displaying a title and a message.

----

.. include:: /manual/Component/Alert/AlertAdditionalDescription.rst

----

Twig Tag
--------

:Tag: **UIAlert**

:Syntax:
 
::

    {% UIAlert Type {Parameters} %}
        Content Goes Here
    {% EndUIAlert %}

:Type:

+----------------------------+-----------------------------------------------------+
| Neutral                    | Make a basis Alert component                        |
+----------------------------+-----------------------------------------------------+
| ForInformation             | Make an Alert component for informational messages  |
+----------------------------+-----------------------------------------------------+
| ForSuccess                 | Make an Alert component for successful messages     |
+----------------------------+-----------------------------------------------------+
| ForWarning                 | Make an Alert component for warning messages        |
+----------------------------+-----------------------------------------------------+
| ForDanger                  | Make an Alert component for danger messages         |
+----------------------------+-----------------------------------------------------+
| ForFailure                 | Make an Alert component for failure messages        |
+----------------------------+-----------------------------------------------------+
| WithBrandingPrimaryColor   | Make an Alert component with primary color scheme   |
+----------------------------+-----------------------------------------------------+
| WithBrandingSecondaryColor | Make an Alert component with secondary color scheme |
+----------------------------+-----------------------------------------------------+

:Alert *Neutral* parameters:

+----------+--------+----------+------+-------------------------------------------------+
| sTitle   | string | optional | ''   | Title of the alert                              |
+----------+--------+----------+------+-------------------------------------------------+
| sContent | string | optional | ''   | The raw HTML content, must be already sanitized |
+----------+--------+----------+------+-------------------------------------------------+
| sId      | string | optional | NULL | id of the html block                            |
+----------+--------+----------+------+-------------------------------------------------+

:Alert *ForInformation* parameters:

+----------+--------+----------+------+-------------------------------------------------+
| sTitle   | string | optional | ''   | Title of the alert                              |
+----------+--------+----------+------+-------------------------------------------------+
| sContent | string | optional | ''   | The raw HTML content, must be already sanitized |
+----------+--------+----------+------+-------------------------------------------------+
| sId      | string | optional | NULL | id of the html block                            |
+----------+--------+----------+------+-------------------------------------------------+

:Alert *ForSuccess* parameters:

+----------+--------+----------+------+-------------------------------------------------+
| sTitle   | string | optional | ''   | Title of the alert                              |
+----------+--------+----------+------+-------------------------------------------------+
| sContent | string | optional | ''   | The raw HTML content, must be already sanitized |
+----------+--------+----------+------+-------------------------------------------------+
| sId      | string | optional | NULL |                                                 |
+----------+--------+----------+------+-------------------------------------------------+

:Alert *ForWarning* parameters:

+----------+--------+----------+------+-------------------------------------------------+
| sTitle   | string | optional | ''   | Title of the alert                              |
+----------+--------+----------+------+-------------------------------------------------+
| sContent | string | optional | ''   | The raw HTML content, must be already sanitized |
+----------+--------+----------+------+-------------------------------------------------+
| sId      | string | optional | NULL | id of the html block                            |
+----------+--------+----------+------+-------------------------------------------------+

:Alert *ForDanger* parameters:

+----------+--------+----------+------+-------------------------------------------------+
| sTitle   | string | optional | ''   | Title of the alert                              |
+----------+--------+----------+------+-------------------------------------------------+
| sContent | string | optional | ''   | The raw HTML content, must be already sanitized |
+----------+--------+----------+------+-------------------------------------------------+
| sId      | string | optional | NULL | id of the html block                            |
+----------+--------+----------+------+-------------------------------------------------+

:Alert *ForFailure* parameters:

+----------+--------+----------+------+-------------------------------------------------+
| sTitle   | string | optional | ''   | Title of the alert                              |
+----------+--------+----------+------+-------------------------------------------------+
| sContent | string | optional | ''   | The raw HTML content, must be already sanitized |
+----------+--------+----------+------+-------------------------------------------------+
| sId      | string | optional | NULL | id of the html block                            |
+----------+--------+----------+------+-------------------------------------------------+

:Alert *WithBrandingPrimaryColor* parameters:

+----------+--------+----------+------+-------------------------------------------------+
| sTitle   | string | optional | ''   | Title of the alert                              |
+----------+--------+----------+------+-------------------------------------------------+
| sContent | string | optional | ''   | The raw HTML content, must be already sanitized |
+----------+--------+----------+------+-------------------------------------------------+
| sId      | string | optional | NULL | id of the html block                            |
+----------+--------+----------+------+-------------------------------------------------+

:Alert *WithBrandingSecondaryColor* parameters:

+----------+--------+----------+------+-------------------------------------------------+
| sTitle   | string | optional | ''   | Title of the alert                              |
+----------+--------+----------+------+-------------------------------------------------+
| sContent | string | optional | ''   | The raw HTML content, must be already sanitized |
+----------+--------+----------+------+-------------------------------------------------+
| sId      | string | optional | NULL | id of the html block                            |
+----------+--------+----------+------+-------------------------------------------------+

:Alert common parameters:

+-------------------+----------+------------------------------------------------------------------------+
| AddCSSClass       | string   | CSS class to add to the generated html block                           |
+-------------------+----------+------------------------------------------------------------------------+
| AddCSSClasses     | array    | like <code>['ibo-is-hidden', 'ibo-alert--body']</code>                 |
+-------------------+----------+------------------------------------------------------------------------+
| AddCssFileRelPath | string   |                                                                        |
+-------------------+----------+------------------------------------------------------------------------+
| AddDeferredBlock  | iUIBlock |                                                                        |
+-------------------+----------+------------------------------------------------------------------------+
| AddHtml           | string   |                                                                        |
+-------------------+----------+------------------------------------------------------------------------+
| AddJsFileRelPath  | string   |                                                                        |
+-------------------+----------+------------------------------------------------------------------------+
| AddSubBlock       | iUIBlock |                                                                        |
+-------------------+----------+------------------------------------------------------------------------+
| CSSClasses        | array    | like <code>['ibo-is-hidden', 'ibo-alert--body']</code>                 |
+-------------------+----------+------------------------------------------------------------------------+
| Color             | string   | Color of the alert (check CSS classes ibo-is-<color> for colors)       |
+-------------------+----------+------------------------------------------------------------------------+
| Content           | string   | The raw HTML content, must be already sanitized                        |
+-------------------+----------+------------------------------------------------------------------------+
| DataAttributes    | array    | Array of data attributes in the format ['name' => 'value']             |
+-------------------+----------+------------------------------------------------------------------------+
| DeferredBlocks    | array    |                                                                        |
+-------------------+----------+------------------------------------------------------------------------+
| IsClosable        | bool     | Indicates if the user can remove the alert from the screen             |
+-------------------+----------+------------------------------------------------------------------------+
| IsCollapsible     | bool     | Indicates if the user can collapse the alert to display only the title |
+-------------------+----------+------------------------------------------------------------------------+
| IsHidden          | bool     | Indicates if the block is hidden by default                            |
+-------------------+----------+------------------------------------------------------------------------+
| OpenedByDefault   | bool     | Indicates if the alert is collapsed or not by default                  |
+-------------------+----------+------------------------------------------------------------------------+
| SubBlocks         | array    |                                                                        |
+-------------------+----------+------------------------------------------------------------------------+
| Title             | string   | Title of the alert                                                     |
+-------------------+----------+------------------------------------------------------------------------+

----

.. include:: /manual/Component/Alert/AlertFooter.rst

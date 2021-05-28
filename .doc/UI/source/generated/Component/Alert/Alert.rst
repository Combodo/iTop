.. Copyright (C) 2010-2021 Combodo SARL
.. http://opensource.org/licenses/AGPL-3.0

.. _Alert:

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

+---------------------------------------------------------------------+-----------------------------------------------------+
| :ref:`Neutral <AlertNeutral>`                                       | Make a basis Alert component                        |
+---------------------------------------------------------------------+-----------------------------------------------------+
| :ref:`ForInformation <AlertForInformation>`                         | Make an Alert component for informational messages  |
+---------------------------------------------------------------------+-----------------------------------------------------+
| :ref:`ForSuccess <AlertForSuccess>`                                 | Make an Alert component for successful messages     |
+---------------------------------------------------------------------+-----------------------------------------------------+
| :ref:`ForWarning <AlertForWarning>`                                 | Make an Alert component for warning messages        |
+---------------------------------------------------------------------+-----------------------------------------------------+
| :ref:`ForDanger <AlertForDanger>`                                   | Make an Alert component for danger messages         |
+---------------------------------------------------------------------+-----------------------------------------------------+
| :ref:`ForFailure <AlertForFailure>`                                 | Make an Alert component for failure messages        |
+---------------------------------------------------------------------+-----------------------------------------------------+
| :ref:`WithBrandingPrimaryColor <AlertWithBrandingPrimaryColor>`     | Make an Alert component with primary color scheme   |
+---------------------------------------------------------------------+-----------------------------------------------------+
| :ref:`WithBrandingSecondaryColor <AlertWithBrandingSecondaryColor>` | Make an Alert component with secondary color scheme |
+---------------------------------------------------------------------+-----------------------------------------------------+

.. _AlertNeutral:

Alert Neutral
^^^^^^^^^^^^^

:syntax:

::

    {% UIAlert Neutral {sTitle:'value', sContent:'value', sId:'value'} %}
        Content Goes Here
    {% EndUIAlert %}

:parameters:

+----------+--------+----------+------+-------------------------------------------------+
| sTitle   | string | optional | ''   | Title of the alert                              |
+----------+--------+----------+------+-------------------------------------------------+
| sContent | string | optional | ''   | The raw HTML content, must be already sanitized |
+----------+--------+----------+------+-------------------------------------------------+
| sId      | string | optional | NULL | id of the html block                            |
+----------+--------+----------+------+-------------------------------------------------+

.. _AlertForInformation:

Alert ForInformation
^^^^^^^^^^^^^^^^^^^^

:syntax:

::

    {% UIAlert ForInformation {sTitle:'value', sContent:'value', sId:'value'} %}
        Content Goes Here
    {% EndUIAlert %}

:parameters:

+----------+--------+----------+------+-------------------------------------------------+
| sTitle   | string | optional | ''   | Title of the alert                              |
+----------+--------+----------+------+-------------------------------------------------+
| sContent | string | optional | ''   | The raw HTML content, must be already sanitized |
+----------+--------+----------+------+-------------------------------------------------+
| sId      | string | optional | NULL | id of the html block                            |
+----------+--------+----------+------+-------------------------------------------------+

.. _AlertForSuccess:

Alert ForSuccess
^^^^^^^^^^^^^^^^

:syntax:

::

    {% UIAlert ForSuccess {sTitle:'value', sContent:'value', sId:'value'} %}
        Content Goes Here
    {% EndUIAlert %}

:parameters:

+----------+--------+----------+------+-------------------------------------------------+
| sTitle   | string | optional | ''   | Title of the alert                              |
+----------+--------+----------+------+-------------------------------------------------+
| sContent | string | optional | ''   | The raw HTML content, must be already sanitized |
+----------+--------+----------+------+-------------------------------------------------+
| sId      | string | optional | NULL |                                                 |
+----------+--------+----------+------+-------------------------------------------------+

.. _AlertForWarning:

Alert ForWarning
^^^^^^^^^^^^^^^^

:syntax:

::

    {% UIAlert ForWarning {sTitle:'value', sContent:'value', sId:'value'} %}
        Content Goes Here
    {% EndUIAlert %}

:parameters:

+----------+--------+----------+------+-------------------------------------------------+
| sTitle   | string | optional | ''   | Title of the alert                              |
+----------+--------+----------+------+-------------------------------------------------+
| sContent | string | optional | ''   | The raw HTML content, must be already sanitized |
+----------+--------+----------+------+-------------------------------------------------+
| sId      | string | optional | NULL | id of the html block                            |
+----------+--------+----------+------+-------------------------------------------------+

.. _AlertForDanger:

Alert ForDanger
^^^^^^^^^^^^^^^

:syntax:

::

    {% UIAlert ForDanger {sTitle:'value', sContent:'value', sId:'value'} %}
        Content Goes Here
    {% EndUIAlert %}

:parameters:

+----------+--------+----------+------+-------------------------------------------------+
| sTitle   | string | optional | ''   | Title of the alert                              |
+----------+--------+----------+------+-------------------------------------------------+
| sContent | string | optional | ''   | The raw HTML content, must be already sanitized |
+----------+--------+----------+------+-------------------------------------------------+
| sId      | string | optional | NULL | id of the html block                            |
+----------+--------+----------+------+-------------------------------------------------+

.. _AlertForFailure:

Alert ForFailure
^^^^^^^^^^^^^^^^

:syntax:

::

    {% UIAlert ForFailure {sTitle:'value', sContent:'value', sId:'value'} %}
        Content Goes Here
    {% EndUIAlert %}

:parameters:

+----------+--------+----------+------+-------------------------------------------------+
| sTitle   | string | optional | ''   | Title of the alert                              |
+----------+--------+----------+------+-------------------------------------------------+
| sContent | string | optional | ''   | The raw HTML content, must be already sanitized |
+----------+--------+----------+------+-------------------------------------------------+
| sId      | string | optional | NULL | id of the html block                            |
+----------+--------+----------+------+-------------------------------------------------+

.. _AlertWithBrandingPrimaryColor:

Alert WithBrandingPrimaryColor
^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^

:syntax:

::

    {% UIAlert WithBrandingPrimaryColor {sTitle:'value', sContent:'value', sId:'value'} %}
        Content Goes Here
    {% EndUIAlert %}

:parameters:

+----------+--------+----------+------+-------------------------------------------------+
| sTitle   | string | optional | ''   | Title of the alert                              |
+----------+--------+----------+------+-------------------------------------------------+
| sContent | string | optional | ''   | The raw HTML content, must be already sanitized |
+----------+--------+----------+------+-------------------------------------------------+
| sId      | string | optional | NULL | id of the html block                            |
+----------+--------+----------+------+-------------------------------------------------+

.. _AlertWithBrandingSecondaryColor:

Alert WithBrandingSecondaryColor
^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^

:syntax:

::

    {% UIAlert WithBrandingSecondaryColor {sTitle:'value', sContent:'value', sId:'value'} %}
        Content Goes Here
    {% EndUIAlert %}

:parameters:

+----------+--------+----------+------+-------------------------------------------------+
| sTitle   | string | optional | ''   | Title of the alert                              |
+----------+--------+----------+------+-------------------------------------------------+
| sContent | string | optional | ''   | The raw HTML content, must be already sanitized |
+----------+--------+----------+------+-------------------------------------------------+
| sId      | string | optional | NULL | id of the html block                            |
+----------+--------+----------+------+-------------------------------------------------+

Alert common parameters
^^^^^^^^^^^^^^^^^^^^^^^
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

.. Copyright (C) 2010-2021 Combodo SARL
.. http://opensource.org/licenses/AGPL-3.0

Alert
=====

Alerts are the main component to give feedback to the user or communicate page specific to system wide messages.

Alerts are a rectangular component displaying a title and a message.

----

Output Result
-------------

.. include:: OutputExamples.rst

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

+------------------------------+-----------------------------------------------------+
| *ForSuccess*                 | Create a *Success Alert*                            |
+------------------------------+-----------------------------------------------------+
| *ForInformation*             | Create an *Information Alert*                       |
+------------------------------+-----------------------------------------------------+
| *ForWarning*                 | Create an *Warning Alert*                           |
+------------------------------+-----------------------------------------------------+
| *ForFailure*                 | Create an *Failure Alert*                           |
+------------------------------+-----------------------------------------------------+
| *ForDanger*                  | Create an *Danger Alert*                            |
+------------------------------+-----------------------------------------------------+
| *Neutral*                    | Create an *Basis Alert*                             |
+------------------------------+-----------------------------------------------------+
| *WithBrandingPrimaryColor*   | Create an alert having the branding primary color   |
+------------------------------+-----------------------------------------------------+
| *WithBrandingSecondaryColor* | Create an alert having the branding secondary color |
+------------------------------+-----------------------------------------------------+

:Alert common parameters:

+-------------------+--------+----------+----------------------------------+
| *sTiTle*          | string | optional | Title of the alert               |
+-------------------+--------+----------+----------------------------------+
| *sContent*        | string | optional | Collapsible content of the alert |
+-------------------+--------+----------+----------------------------------+
| *sId*             | string | optional | ID of the HTML block             |
+-------------------+--------+----------+----------------------------------+
| *IsCollapsible*   | bool   | optional | can be collapsed or not          |
+-------------------+--------+----------+----------------------------------+
| *IsClosable*      | bool   | optional | can be closed or not             |
+-------------------+--------+----------+----------------------------------+
| *OpenedByDefault* | bool   | optional | Opened or not                    |
+-------------------+--------+----------+----------------------------------+

:See also: :ref:`UIBlock Common parameters <UIBlock_parameters>`

----

Examples
--------

.. include:: Examples.rst
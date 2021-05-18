.. Copyright (C) 2010-2021 Combodo SARL
.. http://opensource.org/licenses/AGPL-3.0

Button
======

*In Progress*

----

Output Result
-------------

:Example Buttons:

.. image:: Button-all.png

----

Twig Tag
--------

:Tag: **UIButton**

:Syntax:

::

    {% UIButton Type {Parameters} %}
        Content Goes Here
    {% EndUIButton %}

:Type:

+------------------------------+-----------------------------------------------------+
| *Standard*                   | Create a *Default Button*                           |
+------------------------------+-----------------------------------------------------+

:Button common parameters:

+-------------------+--------+-----------+----------------------------------+
| *sId*             | string | optional  | ID of the HTML block             |
+-------------------+--------+-----------+----------------------------------+

:See also: :ref:`UIBlock Common parameters <UIBlock_parameters>`

----

Examples
--------

Example to generate a button::

    {% UIButton Type {Parameters} %}
        Content Goes Here
    {% EndUIButton %}


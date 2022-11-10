.. Copyright (C) 2010-2021 Combodo SARL
.. http://opensource.org/licenses/AGPL-3.0

Twig Base Presentation
======================

This feature is intended to help extension creators to design forms in a *modern* way.

The **Twig Base** feature is based on MVC structure:

.. image:: MVC.png

When creating an extension following this structure, some parts have to be done:

:Model:
    Optional part to define the specific data model for the extension

:Service:
    Recommended part to produce the data to be displayed

:Controller:
    Mandatory part to gather the data from the *Service* and display using the *View*.
    The *Controller* contains an automatic routing mechanism to be selected by the *operation* parameter.

:View:
    Mandatory part to display the data given by the *Controller*

:End point:
    Mandatory part receiving the request and calling the *Controller*
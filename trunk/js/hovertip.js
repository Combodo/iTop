/**
 *  Hovertip - easy and elegant tooltips
 *  
 *  By Dave Cohen <http://dave-cohen.com>
 *  With ideas and and javascript code borrowed from many folks.
 *  (See URLS in the comments)
 *  
 *  Licensed under GPL. 
 *  Requires jQuery.js.  <http://jquery.com>, 
 *  which may be distributed under a different licence.
 *  
 *  $Date: 2006-09-15 12:49:19 -0700 (Fri, 15 Sep 2006) $
 *  $Rev: $
 *  $Id:$
 *  
 *  This plugin helps you create tooltips.  It supports:
 *  
 *  hovertips - these appear under the mouse when mouse is over the target
 *  element.
 *  
 *  clicktips - these appear in the document when the target element is
 *  clicked.
 *  
 *  You may define behaviors for additional types of tooltips.
 *  
 *  There are a variety of ways to add tooltips.  Each of the following is
 *  supported:
 *  
 *  <p>blah blah blah 
 *  <span>important term</span>
 *  <span class="tooltip">text that appears.</span> 
 *  blah blah blah</p>
 *  
 *  or,
 *  
 *  <p>blah blah blah 
 *  <span hovertip="termdefinition">important term</span>
 *  blah blah blah</p>
 *  <div id="termdefinition" class="hovertip"><h1>term definition</h1><p>the term means...</p></div>
 *  
 *  or, 
 *  
 *  <p>blah blah blah 
 *  <span id="term">important term</span>
 *  blah blah blah</p>
 *  <div target="term" class="hovertip"><h1>term definition</h1><p>the term means...</p></div>
 *  
 *  
 *  Hooks are available to customize both the behavior of activated tooltips,
 *  and the syntax used to mark them up.
 *  
 */


//// mouse events ////
/**
 * To make hovertips appear correctly we need the exact mouse position.
 * These functions make that possible.
 */

// use globals to track mouse position
var hovertipMouseX;
var hovertipMouseY;
function hovertipMouseUpdate(e) {
  var mouse = hovertipMouseXY(e);
  hovertipMouseX = mouse[0];
  hovertipMouseY = mouse[1];
}

// http://www.howtocreate.co.uk/tutorials/javascript/eventinfo
function hovertipMouseXY(e) {
  if( !e ) {
    if( window.event ) {
      //Internet Explorer
      e = window.event;
    } else {
      //total failure, we have no way of referencing the event
      return;
    }
  }
  if( typeof( e.pageX ) == 'number' ) {
    //most browsers
    var xcoord = e.pageX;
    var ycoord = e.pageY;
  } else if( typeof( e.clientX ) == 'number' ) {
    //Internet Explorer and older browsers
    //other browsers provide this, but follow the pageX/Y branch
    var xcoord = e.clientX;
    var ycoord = e.clientY;
    var badOldBrowser = ( window.navigator.userAgent.indexOf( 'Opera' ) + 1 ) ||
      ( window.ScriptEngine && ScriptEngine().indexOf( 'InScript' ) + 1 ) ||
      ( navigator.vendor == 'KDE' );
    if( !badOldBrowser ) {
      if( document.body && ( document.body.scrollLeft || document.body.scrollTop ) ) {
        //IE 4, 5 & 6 (in non-standards compliant mode)
        xcoord += document.body.scrollLeft;
        ycoord += document.body.scrollTop;
      } else if( document.documentElement && ( document.documentElement.scrollLeft || document.documentElement.scrollTop ) ) {
        //IE 6 (in standards compliant mode)
        xcoord += document.documentElement.scrollLeft;
        ycoord += document.documentElement.scrollTop;
      }
    }
  } else {
    //total failure, we have no way of obtaining the mouse coordinates
    return;
  }
  return [xcoord, ycoord];
}



//// target selectors ////

/**
 * These selectors find the targets for a given tooltip element.  
 * Several methods are supported.  
 * 
 * You may write your own selector functions to customize.
 */

/**
 * For this model:
 * <span hovertip="ht1">target term</span>...
 * <div class="hovertip" id="ht1">tooltip text</div>
 */
targetSelectById = function(el, config) {
  var id;
  var selector;
  if (id = el.getAttribute('id')) {
    selector = '*[@'+config.attribute+'=\''+id+'\']';
    return $(selector);
  }
};

/**
 * For this model:
 * <span id="ht1">target term</span>...
 * <div class="hovertip" target="ht1">tooltip text</div>
 */
targetSelectByTargetAttribute = function(el, config) {
  target_list = el.getAttribute('target');
  if (target_list) {
    // use for attribute to specify targets
    target_ids = target_list.split(' ');
    var selector = '#' + target_ids.join(',#');
    return $(selector);
  }
};

/**
 * For this model:
 * <span>target term</span><span class="hovertip">tooltip text</span>
 */
targetSelectByPrevious = function(el, config) {
  return $(el.previousSibling);
}

/**
 * Make all siblings targets.  Experimental.
 */
targetSelectBySiblings = function(el, config) {
  return $(el).siblings();
}

//// prepare tip elements ////

/**
 * The tooltip element needs special preparation.  You may define your own
 * prepare functions to cusomize the behavior.
 */

// adds a close link to clicktips
clicktipPrepareWithCloseLink = function(o, config) {
  return o.append("<a class='clicktip_close'><span>close</span></a>")
  .find('a.clicktip_close').click(function(e) {
      o.hide();
      return false;
    }).end(); 
};

// ensure that hovertips do not disappear when the mouse is over them.
// also position the hovertip as an absolutely positioned child of body.
hovertipPrepare = function(o, config) {
  return o.hover(function() {
      hovertipHideCancel(this);
    }, function() {
      hovertipHideLater(this);
    }).css('position', 'absolute').each(hovertipPosition);
};

// do not modify tooltips when preparing
hovertipPrepareNoOp = function(o, config) {
  return o;
}

//// manipulate tip elements /////
/**
 * A variety of functions to modify tooltip elements
 */

// move tooltips to body, so they are not descended from other absolutely
// positioned elements.
hovertipPosition = function(i) {
  document.body.appendChild(this);
}

hovertipIsVisible = function(el) {
  return (jQuery.css(el, 'display') != 'none');
}

// show the tooltip under the mouse.
// Introduce a delay, so tip appears only if cursor rests on target for more than an instant.
hovertipShowUnderMouse = function(el) {
  hovertipHideCancel(el);
  if (!hovertipIsVisible(el)) {
    el.ht.showing = // keep reference to timer
      window.setTimeout(function() {
          el.ht.tip.css({
              'position':'absolute',
                'top': hovertipMouseY + 'px',
                'left': hovertipMouseX + 'px'})
            .show();
        }, el.ht.config.showDelay);
  }
};

// do not hide
hovertipHideCancel = function(el) {
  if (el.ht.hiding) {
    window.clearTimeout(el.ht.hiding);
    el.ht.hiding = null;
  }  
};

// Hide a tooltip, but only after a delay.
// The delay allow the tip to remain when user moves mouse from target to tooltip
hovertipHideLater = function(el) {
  if (el.ht.showing) {
    window.clearTimeout(el.ht.showing);
    el.ht.showing = null;
  }
  if (el.ht.hiding) {
    window.clearTimeout(el.ht.hiding);
    el.ht.hiding = null;
  }
  el.ht.hiding = 
  window.setTimeout(function() {
      if (el.ht.hiding) {
        // fadeOut, slideUp do not work on Konqueror
        el.ht.tip.hide();
      }
    }, el.ht.config.hideDelay);
};


//// prepare target elements ////
/**
 * As we prepared the tooltip elements, the targets also need preparation.
 * 
 * You may define your own custom behavior.
 */

// when clicked on target, toggle visibilty of tooltip
clicktipTargetPrepare = function(o, el, config) {
  return o.addClass(config.attribute + '_target')
  .click(function() {
      el.ht.tip.toggle();
      return false;
    });
};

// when hover over target, make tooltip appear
hovertipTargetPrepare = function(o, el, config) {
  return o.addClass(config.attribute + '_target')
  .hover(function() {
      // show tip when mouse over target
      hovertipShowUnderMouse(el);
    },
    function() {
      // hide the tip
      // add a delay so user can move mouse from the target to the tip
      hovertipHideLater(el);
    });
};


/**
 * hovertipActivate() is our jQuery plugin function.  It turns on hovertip or
 * clicktip behavior for a set of elements.
 * 
 * @param config 
 * controls aspects of tooltip behavior.  Be sure to define
 * 'attribute', 'showDelay' and 'hideDelay'.
 * 
 * @param targetSelect
 * function finds the targets of a given tooltip element.
 * 
 * @param tipPrepare
 * function alters the tooltip to display and behave properly
 * 
 * @param targetPrepare
 * function alters the target to display and behave properly.
 */
jQuery.fn.hovertipActivate = function(config, targetSelect, tipPrepare, targetPrepare) {
  //alert('activating ' + this.size());
  // unhide so jquery show/hide will work.
  return this.css('display', 'block')
  .hide() // don't show it until click
  .each(function() {
      if (!this.ht)
        this.ht = new Object();
      this.ht.config = config;
      
      // find our targets
      var targets = targetSelect(this, config);
      if (targets && targets.size()) {
        if (!this.ht.targets)
          this.ht.targets = targetPrepare(targets, this, config);
        else
          this.ht.targets.add(targetPrepare(targets, this, config));
        
        // listen to mouse move events so we know exatly where to place hovetips
        targets.mousemove(hovertipMouseUpdate);
        
        // prepare the tooltip element
        // is it bad form to call $(this) here?
        if (!this.ht.tip)
          this.ht.tip = tipPrepare($(this), config);
      }
      
    })
  ;
};

/**
 * Here's an example ready function which shows how to enable tooltips.
 * 
 * You can make this considerably shorter by choosing only the markup style(s)
 * you will use.
 * 
 * You may also remove the code that wraps hovertips to produce drop-shadow FX
 * 
 * Invoke this function or one like it from your $(document).ready(). 
 *  
 *  Here, we break the action up into several timout callbacks, to avoid
 *  locking up browsers.
 */
function hovertipInit() {
  // specify the attribute name we use for our clicktips
  var clicktipConfig = {'attribute':'clicktip'};
  
  /**
   * To enable this style of markup (id on tooltip):
   * <span clicktip="foo">target</span>...
   * <div id="foo" class="clicktip">blah blah</div>
   */
  window.setTimeout(function() {
    $('.clicktip').hovertipActivate(clicktipConfig,
                                    targetSelectById,
                                    clicktipPrepareWithCloseLink,
                                    clicktipTargetPrepare);
  }, 0);
  
  /**
   * To enable this style of markup (id on target):
   * <span id="foo">target</span>...
   * <div target="foo" class="clicktip">blah blah</div>
   */
  window.setTimeout(function() {
    $('.clicktip').hovertipActivate(clicktipConfig,
                                    targetSelectByTargetAttribute,
                                    clicktipPrepareWithCloseLink,
                                    clicktipTargetPrepare);
  }, 0);
  
  // specify our configuration for hovertips, including delay times (millisec)
  var hovertipConfig = {'attribute':'hovertip',
                        'showDelay': 300,
                        'hideDelay': 700};
  
  // use <div class='hovertip'>blah blah</div>
  var hovertipSelect = 'div.hovertip';
  
  // OPTIONAL: here we wrap each hovertip to apply special effect. (i.e. drop shadow):
  $(hovertipSelect).css('display', 'block').addClass('hovertip_wrap3').
    wrap("<div class='hovertip_wrap0'><div class='hovertip_wrap1'><div class='hovertip_wrap2'>" + 
         "</div></div></div>").each(function() {
           // fix class and attributes for newly wrapped elements
           var tooltip = this.parentNode.parentNode.parentNode;
           if (this.getAttribute('target'))
             tooltip.setAttribute('target', this.getAttribute('target'));
           if (this.getAttribute('id')) {
             var id = this.getAttribute('id');
             this.removeAttribute('id');
             tooltip.setAttribute('id', id);
           }
         });
  hovertipSelect = 'div.hovertip_wrap0';
  
  // end optional FX section
  
  /**
   * To enable this style of markup (id on tooltip):
   * <span hovertip="foo">target</span>...
   * <div id="foo" class="hovertip">blah blah</div>
   */
  window.setTimeout(function() {
    $(hovertipSelect).hovertipActivate(hovertipConfig,
                                       targetSelectById,
                                       hovertipPrepare,
                                       hovertipTargetPrepare);
  }, 0);

  /**
   * To enable this style of markup (id on target):
   * <span id="foo">target</span>...
   * <div target="foo" class="hovertip">blah blah</div>
   */
  window.setTimeout(function() {
    $(hovertipSelect).hovertipActivate(hovertipConfig,
                                       targetSelectByTargetAttribute,
                                       hovertipPrepare,
                                       hovertipTargetPrepare);
  }, 0);
  
  /**
   * This next section enables this style of markup:
   * <foo><span>target</span><span class="hovertip">blah blah</span></foo>
   * 
   * With drop shadow effect.
   * 
   */
  var hovertipSpanSelect = 'span.hovertip';
  // activate hovertips with wrappers for FX (drop shadow):
  $(hovertipSpanSelect).css('display', 'block').addClass('hovertip_wrap3').
    wrap("<span class='hovertip_wrap0'><span class='hovertip_wrap1'><span class='hovertip_wrap2'>" + 
         "</span></span></span>").each(function() {
           // fix class and attributes for newly wrapped elements
           var tooltip = this.parentNode.parentNode.parentNode;
           if (this.getAttribute('target'))
             tooltip.setAttribute('target', this.getAttribute('target'));
           if (this.getAttribute('id')) {
             var id = this.getAttribute('id');
             this.removeAttribute('id');
             tooltip.setAttribute('id', id);
           }
         });
  hovertipSpanSelect = 'span.hovertip_wrap0';

  window.setTimeout(function() {
    $(hovertipSpanSelect)
      .hovertipActivate(hovertipConfig,
                        targetSelectByPrevious,
                        hovertipPrepare,
                        hovertipTargetPrepare);
  }, 0);
}

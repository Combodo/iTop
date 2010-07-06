package iTop
{
	import flash.display.*;
	import flash.geom.*;
	import flash.net.*;
	import flash.events.*;
	import iTop.GraphNode;
	import fl.controls.Slider; 
	import fl.events.SliderEvent; 
	import fl.controls.Label; 
 	
	// The main canvas
	public class Navigator extends MovieClip
	{
		protected var m_oLoader:URLLoader;
		protected var m_aNodes:Object;
		protected var m_aLinks:Array;
		protected var m_oRootNode:GraphNode;
		protected var m_oCanvas:NavigatorCanvas;
		public var m_bChildDragging:Boolean;
		
		// Parameters
		protected var m_sStartPosition:String;
		protected var m_sDataUrl:String;
		protected var m_sDetailsUrl:String;
		protected var m_sRelation:String;
		protected var m_sObjClass:String;
		protected var m_sObjId:String;
		
		// Constants
		protected var m_RADIUS = 120;
		protected var m_ITEMS_PER_ROW = 8;
		protected var m_fZoom:Number;
		
		// Constructor
		public function Navigator()
		{
			m_aLinks = new Array();
			m_aNodes = new Array();
			m_fZoom = 1;
			initParameters();
			doLoadData();
			addEventListener(Event.ENTER_FRAME, initGraphics);
		}
		
		protected function initParameters():void
		{
			
			m_sDataUrl = ReadParam('xmlUrl', 'http://localhost:81/pages/xml.navigator.php?operation=relation');
			m_sDetailsUrl = ReadParam('drillUrl', 'http://localhost/pages/UI.php?operation=details');
			m_sRelation = ReadParam('relation', 'impacts');
			m_sObjClass = ReadParam('obj_class', 'Server');
			m_sObjId = ReadParam('obj_id', '1');
			m_sStartPosition = ReadParam('start_pos', 'left');
		}
		
		function initGraphics(event:Event):void
		{
			m_oCanvas = new NavigatorCanvas(); // All drawings will occur here
			addChild(m_oCanvas); 
			m_oCanvas.scaleX = m_fZoom;
			m_oCanvas.scaleY = m_fZoom;
			// Handle listeners...
			removeEventListener(Event.ENTER_FRAME,initGraphics);
			addEventListener(Event.ENTER_FRAME, drawLines);
			m_oZoomSlider.value = 100;
			m_oZoomSlider.addEventListener(SliderEvent.CHANGE, onZoomChange);
			stage.addEventListener(MouseEvent.MOUSE_DOWN, mouseDown)  
			stage.addEventListener(MouseEvent.MOUSE_UP, mouseReleased); 
		}
		function mouseDown(event:MouseEvent):void 
		{ 
			trace("Click in canvas");
			if (!m_bChildDragging)
			{
				m_oCanvas.startDrag(); 
			}
		} 
		
		function mouseReleased(event:MouseEvent):void 
		{ 
			if (!m_bChildDragging)
			{
				m_oCanvas.stopDrag(); 
			}
		}
		
		function onZoomChange(event:SliderEvent):void
		{ 
    		m_fZoom = event.value/100;
			m_oCanvas.scaleX = m_fZoom;
			m_oCanvas.scaleY = m_fZoom;
		}
		
		function doLoadData()
		{
			var myString:String = m_sDataUrl+'?relation='+m_sRelation+'&class='+m_sObjClass+'&id='+m_sObjId;
			trace("Requesting:"+myString);
			var myXMLURL:URLRequest = new URLRequest(myString);
			m_oLoader = new URLLoader();
			m_oLoader.addEventListener(Event.COMPLETE, onXMLLoadComplete);
			m_oLoader.addEventListener(SecurityErrorEvent.SECURITY_ERROR, onXMLLoadError);
			m_oLoader.addEventListener(IOErrorEvent.IO_ERROR, onXMLLoadError);
			m_oLoader.load(myXMLURL);
		}
		
		function onXMLLoadComplete(event:Event):void
		{
			var myXML:XML = XML(m_oLoader.data);
			//trace("Data loaded." + myXML);
			//trace("===========================");
			parseXMLData(null, myXML, 0);
			m_sTitle.text = myXML.attribute("title");
			m_oZoomSlider.enabled = true;
			removeChild(m_oPreloader);
		}

		function onXMLLoadError(event:IOErrorEvent):void
		{
			trace("An error occured:" + Event);
		}
		
		function parseXMLData(oParentNode:GraphNode, oXMLData:XML, iChildIndex:Number)
		{
			//trace(oXMLData.child("node").length());
			var oNode:GraphNode;
			oNode  = addNode(oParentNode, oXMLData.child("node")[0], iChildIndex);
			if (oParentNode != null)
			{
				AddLink(oParentNode.GetKey(), oNode.GetKey());
			}
			//trace('Root node:'+oRoot.toString());
			var oLinks = oXMLData.child("node")[0].links;
			var iChildIndex:Number = 0;
			if (oLinks.length() > 0)
			{
				//trace('links: '+oLinks.toString());
				var oLink = oLinks.link;
				for each(var oChild:XML in oLink)
				{
					parseXMLData(oNode, oChild, iChildIndex);
					iChildIndex++;
				}
			}
		}
		
		function addNode(oParent:GraphNode, oXMLData:XML, iChildIndex)
		{
			var sClass  = oXMLData.@obj_class;
			var iId = oXMLData.@id;
			var sLabel = oXMLData.@name;
			var sIcon = oXMLData.@icon;
			
			var oNode:GraphNode = GetNode(sClass+'/'+iId);
			if (oNode == null)
			{
				// If the node does not already exist, let's create it
				var oPt:Point = GetNextFreePosition(oParent, iChildIndex);
				var sParentKey = null;
				if (oParent != null)
				{
					sParentKey = oParent.GetKey();
				}
				oNode = new GraphNode(this, oPt, sClass, iId, sLabel, sIcon, sParentKey, m_fZoom);
				this.m_aNodes[oNode.GetKey()] = oNode; //Keep it referenced
				if (oParent == null)
				{
					m_oRootNode = oNode;
				}
				m_oCanvas.addChild(oNode);
			}
			return oNode;
			//trace("class: "+sClass+", id: "+iId+", name: "+sLabel+", Icon: "+sIcon);
		}
		
		function GetNode(sKey)
		{
			if (m_aNodes.hasOwnProperty(sKey))
			{
				return m_aNodes[sKey];
			}
			else
			{
				return null;
			}
		}
		
		function GetNextFreePosition(oParent:GraphNode, iChildIndex:Number):Point
		{
			var oPt:Point = GetInitialPosition();
			var angle:Number = GetInitialAngle();
			if (oParent != null)
			{
				oPt.x = oParent.x;
				oPt.y = oParent.y;
				var sGrandParentKey:String = oParent.GetParentKey();
				if (sGrandParentKey != null)
				{
					var oGrandParent:GraphNode = GetNode(sGrandParentKey);
					var dx:Number = oParent.x - oGrandParent.x;
					var dy:Number = oParent.y - oGrandParent.y;
					if ((dx == 0) && (dy == 0))
					{
						angle = GetInitialAngle();
					}
					else
					{
						angle = Math.atan2(dy, dx);
					}
				}
				var radius = m_RADIUS * Math.floor(iChildIndex / m_ITEMS_PER_ROW);
				angle += iChildIndex*(2*Math.PI) / m_ITEMS_PER_ROW;
				
				oPt.x += m_RADIUS * Math.cos(angle);
				oPt.y += m_RADIUS * Math.sin(angle);
				
				trace("iChildIndex: "+iChildIndex+" x: "+oPt.x+" y: "+oPt.y+" sGdParentKey: "+sGrandParentKey);
			}
			return oPt;
		}
		
		function GetInitialPosition():Point
		{
			trace('width: '+stage.stageWidth+' height: '+stage.stageHeight);
			var oPos:Point = new Point(0,0);
			switch(m_sStartPosition)
			{
				case 'left':
				oPos.x = m_RADIUS;
				oPos.y = stage.stageHeight / 2;
				break;
				
				case 'right':
				oPos.x = stage.stageWidth - m_RADIUS;
				oPos.y = stage.stageHeight / 2;
				break;
				
				case 'top':
				oPos.x = stage.stageWidth/2;
				oPos.y = m_RADIUS;
				break;
				
				case 'bottom':
				oPos.x = stage.stageWidth/2;
				oPos.y = stage.stageHeight - m_RADIUS;
				break;
			}
			return oPos;
		}

		function GetInitialAngle():Number
		{
			var angle:Number;
			switch(m_sStartPosition)
			{
				case 'left':
				angle = 0;
				break;
				
				case 'right':
				angle = Math.PI;
				break;
				
				case 'top':
				angle = -Math.PI / 2;
				break;
				
				case 'right':
				angle = Math.PI / 2;
				break;
			}
			return angle;
		}
				
		function AddLink(sStart:String, sEnd:String)
		{
			var oLink = new Link(sStart, sEnd);
			m_aLinks.push(oLink);
		}
		
		function drawLines(event:Event):void
		{
			m_oCanvas.graphics.clear();
			m_oCanvas.graphics.lineStyle(2,0x666666,100);
			for (var index:String in m_aLinks)
			{
    			var oStartNode:GraphNode = GetNode(m_aLinks[index].GetStart());
				var oEndNode = GetNode(m_aLinks[index].GetEnd());
				m_oCanvas.graphics.moveTo(oStartNode.x, oStartNode.y);
				m_oCanvas.graphics.lineTo(oEndNode.x, oEndNode.y);
				var oMiddlePoint:Point = new Point((oEndNode.x+oStartNode.x)/2, (oEndNode.y+oStartNode.y)/2);
				drawArrow(oMiddlePoint, oEndNode.x - oStartNode.x, oEndNode.y - oStartNode.y);
			}
		}
		function drawArrow(oPt:Point, dx:Number, dy:Number):void
		{
			var l:Number = Math.sqrt(dx*dx+dy*dy);
			var arrowSize:Number = 5;
			m_oCanvas.graphics.lineStyle(2,0x666666,100,false,"normal",CapsStyle.ROUND);
			m_oCanvas.graphics.moveTo(oPt.x, oPt.y);
			m_oCanvas.graphics.lineTo(oPt.x + arrowSize*(dy-dx)/l, oPt.y - arrowSize*(dx+dy)/l);
			m_oCanvas.graphics.moveTo(oPt.x, oPt.y);
			m_oCanvas.graphics.lineTo(oPt.x - arrowSize*(dx+dy)/l, oPt.y - arrowSize*(dy-dx)/l);
		}
		
		public function ReadParam(sName:String, sDefaultValue:String)
		{
			var paramObj:Object = LoaderInfo(this.root.loaderInfo).parameters;
		
			if (paramObj.hasOwnProperty(sName))
			{
				return unescape(paramObj[sName]);
			}
			else
			{
				return sDefaultValue;
			}
		}
	}	
}

class Link extends Object
{
	protected var m_sStart:String;
	protected var m_sEnd:String;
	public function Link(sStartNodeKey:String, sEndNodeKey:String)
	{
		m_sStart = sStartNodeKey;
		m_sEnd = sEndNodeKey;
	}
	
	public function GetStart():String
	{
		return m_sStart;
	}
	public function GetEnd():String
	{
		return m_sEnd;
	}
}

import flash.display.*;
import flash.geom.*;
import flash.events.*;

class NavigatorCanvas extends Sprite
{
	public function NavigatorCanvas()
	{
	}
}

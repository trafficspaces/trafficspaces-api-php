<?php
/**
 ** Copyright (c) 2011 Trafficspaces Inc.
 ** 
 ** Permission is hereby granted, free of charge, to any person obtaining a copy
 ** of this software and associated documentation files (the "Software"), to deal
 ** in the Software without restriction, including without limitation the rights
 ** to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 ** copies of the Software, and to permit persons to whom the Software is
 ** furnished to do so, subject to the following conditions:
 ** 
 ** The above copyright notice and this permission notice shall be included in
 ** all copies or substantial portions of the Software.
 ** 
 ** THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 ** IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 ** FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 ** AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 ** LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 ** OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
 ** THE SOFTWARE.
 ** 
 ** Reference Documentation: http://support.trafficspaces.com/kb/api/api-introduction
 **/
class TrafficspacesPlacementConnector extends TrafficspacesConnector {
	
	public function __construct(TrafficspacesEndPoint $end_point, $resource_path, $resource_class_name) {
		parent::__construct($end_point, $resource_path, $resource_class_name);
	}

	/****************************************************
	 ***********       CRUD FUNCTIONS       *************
	 ****************************************************/

	public function find($placements, $flags, $medium = null, $frame = null, $title = null, $useIframe = true) {

		$params = array("request" => $this->getRequestXMLObject($placements, $flags, $medium, $frame, $title, $useIframe)->asXML());
		return parent::find($params);
	}

	private function getRequestXMLObject($placements, $flags, $medium, $frame, $title, $useIframe) {
		$xmlString = "<?xml version='1.0' encoding='utf-8'?>";
		
		$xmlString .= "<request>";
		{
			if ($placements != null && count($placements) > 0) {
				$xmlString .= "<placements>";
				foreach ($placements as $placement) {
					$xmlString .= $placement->getXML(false);
				}
				$xmlString .= "</placements>";
			}
			if ($flags != null) {
				$xmlString .= $flags->getXML(false);
			}
			if ($medium != null) {
				$xmlString .= "<medium>{$medium}</medium>";
			}
			if ($frame != null) {
				$xmlString .= "<frame>{$frame}</frame>";
			}
			if ($title != null) {
				$xmlString .= "<title>{$title}</title>";
			}
			$xmlString .= "<useiframe>{$useIframe}</useiframe>";
		}
		$xmlString .= "</request>";
		return new SimpleXMLElement($xmlString);
	}
}
?>

<?php
namespace raoul2000\workflow\view;

use yii\base\Widget;
use yii\base\InvalidConfigException;

class WorkflowViewWidget extends Widget
{
	/**
	 * @var mixed the Workflow object to display, or a model attached to a
	 * SimpleWorkflowBehavior. In this case, if the model is in a status, its parent workflow
	 * is used, otherwise the default workflow is used.
	 */
    public $workflow;
    /**
     * @var string Id of the HTML element that is as a container for the
     * workflow view
     */
    public $containerId; 
    /**
     * @var string Id of the VIS javascript objects instance created by this widget to render the workflow. 
     * If not set a unique id is automatically created.
     */
    public $visNetworkId;
    /**
     * @var WorkflowInterface the workflow instance to display
     */
	private $_workflow;
	/** 
	 * @var string unique Id
	 */
	private $_visId;
	
	/**
	 * (non-PHPdoc)
	 * @see \yii\base\Object::init()
	 */
    public function init()
    {
        parent::init();
        if ( ! isset($this->containerId)){
        	throw new InvalidConfigException("Parameter 'containerId' is missing ");
        }
        if ( ! isset($this->visNetworkId)){
        	$this->visNetworkId = uniqid('vis_');
        }        
        if ( ! isset($this->workflow)){
        	throw new InvalidConfigException("Parameter 'workflow' is missing ");
        } 
        
        if ( $this->workflow instanceof \yii\base\Model ) {
        	 
        	if ( ! \raoul2000\workflow\base\SimpleWorkflowBehavior::isAttachedTo($this->workflow)) {
        		throw new InvalidConfigException("The model passed as parameter 'workflow' is not attached to a SimpleWorkflowBehavior.");
        	}

        	$this->_workflow = $this->workflow->getWorkflow();
        	if ( $this->_workflow == null) {
        		$this->_workflow = $this->workflow->getWorkflowSource()->getWorkflow(
        			$this->workflow->getDefaultWorkflowId()
        		);
        	}
        } elseif ( $this->workflow instanceof \raoul2000\workflow\base\WorkflowInterface ) {
        	$this->_workflow = $this->workflow;
        } 
        
        if ( $this->_workflow == null) {
        	throw new InvalidConfigException("Failed to find workflow instance from parameter 'workflow'");
        }
        $this->_visId = uniqid();
    }

    /**
     * @see \yii\base\Widget::run()
     */
    public function run()
    {
        $this->getView()->registerJs(
        	$this->createJs()
        );
        WorkflowViewAsset::register($this->getView());
    }
    
    /**
     * Creates and returns the JS code.
     * The JS code is used to initialize the VIS instances in charge of displaying the workflow.
     * 
     * @return string the JS code
     */
    private function createJs()
    {
    	$nodes = $this->_workflow->getAllStatuses();
    	$trList = [];
    	$nodeList = [];
    	foreach($nodes as $node) {
    		$n = new \stdClass();
    		$n->id = $node->getId();
    		$n->label = $node->getLabel();
    		if( $node->getMetadata('color') ){
    			if( $node->getWorkflow()->getInitialStatusId() == $node->getId() ){
    				$n->borderWidth = 4;
    				$n->color = new \stdClass();
    				$n->color->border = 'rgb(0,255,42)';
    				// 	$n->color->background = $node->getMetadata('color');
    			} else {
    				//	$n->color = $node->getMetadata('color');
    			}
    		}
    		$nodeList[] = $n;
    	
    		$transitions = $node->getTransitions();
    		foreach($transitions as $transition){
    			$t = new \stdClass();
    			$t->from = $n->id;
    			$t->to = $transition->getEndStatus()->getId();
    			$t->arrows = 'to';
    			$trList[] = $t;
    		}
    	}
    	$jsonNodes = \yii\helpers\Json::encode($nodeList);
    	$jsonTransitions = \yii\helpers\Json::encode($trList);
   	
    	$js=<<<EOS
		var {$this->visNetworkId} = new vis.Network(
			document.getElementById('{$this->containerId}'), 
	    	{
			    nodes: new vis.DataSet($jsonNodes),
			    edges: new vis.DataSet($jsonTransitions)
		  	}, 
	    	{				    			    	
				"physics": {
					"solver": "repulsion"
				}
			}
	    );

EOS;
    	return $js;   
    }
}
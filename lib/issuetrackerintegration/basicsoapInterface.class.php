<?php
/**
 * TestLink Open Source Project - http://testlink.sourceforge.net/ 
 *
 * @filesource  basicInterface.class.php
 * @author Michiel Vanthuyne
 *
**/
class basicsoapInterface extends issueTrackerInterface
{
  // Copied from mantis configuration
  private $status_color = array('new'          => '#ffa0a0', # red,
                                'feedback'     => '#ff50a8', # purple
                                'acknowledged' => '#ffd850', # orange
                                'confirmed'    => '#ffffb0', # yellow
                                'assigned'     => '#c8c8ff', # blue
                                'resolved'     => '#cceedd', # buish-green
                                'closed'       => '#e8e8e8'); # light gray
  
  var $defaultResolvedStatus;

  /**
   * Construct and connect to BTS.
   *
   * @param str $type (see tlIssueTracker.class.php $systems property)
   * @param xml $cfg
   **/
  function __construct($type,$config,$name)
  {
    $this->name = $name;
    $this->interfaceViaDB = false;
    $this->methodOpt['buildViewBugLink'] = array('addSummary' => true, 'colorByStatus' => true);

    
    if( $this->setCfg($config) )
    {
      $this->completeCfg();
      #$this->setResolvedStatusCfg();
      $this->guiCfg = array('use_decoration' => true);
      $this->connect();
      
    }  
  }

  
  /**
   * Return the URL to the bugtracking page for viewing 
   * the bug with the given id. 
   *
   * @param int id the bug id
   * 
   * @return string returns a complete URL to view the bug
   **/
  function buildViewBugURL($id)
  {
    return (string)($this->cfg->uriview . urlencode($id));
  }
  
  function connect()
  {
      $this->connected = true;
  }

  function isConnected()
  {
    return $this->connected;
  }

  /**
   * 
   * 
   *
   * 
   **/
  public static function getCfgTemplate()
  {
    $template = "<!-- Template " . __CLASS__ . " -->\n" .
                "<issuetracker>\n" .
                "<uribase>http://www.yoursite.org/</uribase>\n" .
                "<uriview>http://www.yoursite.org/view.php?id=</uriview>\n" .
                "<uricreate>http://www.yoursite.org/newissue.html</uricreate>\n" .
                "</issuetracker>\n";
    return $template;
  }

  /**
   *
   * check for configuration attributes than can be provided on
   * user configuration, but that can be considered standard.
   * If they are MISSING we will use 'these carved on the stone values' 
   * in order to simplify configuration.
   *
   *
   **/
  function completeCfg()
  {
    $base = trim($this->cfg->uribase,"/") . '/' ;
    
    if( !property_exists($this->cfg,'uriview') )
    {
      $this->cfg->uriview = $base . 'view.php?id=';
    }
      
    if( !property_exists($this->cfg,'uricreate') )
    {
      $this->cfg->uricreate = $base;
    }     
  }

    /**
     * checks id for validity
     *
   * @param string issueID
     *
     * @return bool returns true if the bugid has the right format, false else
     **/
    function checkBugIDSyntax($issueID)
    {
      return $this->checkBugIDSyntaxNumeric($issueID);
    }

  public function addIssue($summary,$description,$opt=null)
  {
    return (string)($this->cfg->uricreate);
  }
  
  /**
   *
   **/
  function canCreateViaAPI()
  {
    return false;
  }
  
    /**
   * default implementation for generating a link to the bugtracking page for viewing
   * the bug with the given id in a new page
   *
   * @param int id the bug id
   *
   * @return string returns a complete HTML HREF to view the bug (if found in db)
   *
   * Note: currently not doing any verification
   **/
  function buildViewBugLink($issueID, $opt=null)
  {
    $my['opt'] = $this->methodOpt[__FUNCTION__];
    $my['opt'] = array_merge($my['opt'],(array)$opt);

    $link = "<a href='" . $this->buildViewBugURL($issueID) . "' target='_blank'>";
    $link .= "DCP task ".$issueID."</a>";
    
    $ret = new stdClass();
    $ret->link = $link;
    $ret->isResolved = false;
    $ret->op = true;

    return $ret;
  }
  
    /**
   * @param issueID (can be number of string according to specific BTS)
   *
   * @return bool true if issue exists on BTS
   **/
  function checkBugIDExistence($issueID)
  {
    return true;
  }
  
}

<?php
/* Copyright (C) 2010 Laurent Destailleur  <eldy@users.sourceforge.net>
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 59 Temple Place - Suite 330, Boston, MA 02111-1307, USA.
 */

/**
 *      \file       test/phpunit/SocieteTest.php
 *		\ingroup    test
 *      \brief      PHPUnit test
 *		\remarks	To run this script as CLI:  phpunit filename.php
 */

global $conf,$user,$langs,$db;
//define('TEST_DB_FORCE_TYPE','mysql');	// This is to force using mysql driver
require_once 'PHPUnit/Autoload.php';
require_once dirname(__FILE__).'/../../htdocs/master.inc.php';
require_once dirname(__FILE__).'/../../htdocs/societe/class/societe.class.php';

if (empty($user->id))
{
	print "Load permissions for admin user nb 1\n";
	$user->fetch(1);
	$user->getrights();
}
$conf->global->MAIN_DISABLE_ALL_MAILS=1;


/**
 * Class for PHPUnit tests
 *
 * @backupGlobals disabled
 * @backupStaticAttributes enabled
 * @remarks	backupGlobals must be disabled to have db,conf,user and lang not erased.
 */
class SocieteTest extends PHPUnit_Framework_TestCase
{
	protected $savconf;
	protected $savuser;
	protected $savlangs;
	protected $savdb;

	/**
	 * Constructor
	 * We save global variables into local variables
	 *
	 * @return SocieteTest
	 */
	function SocieteTest()
	{
		//$this->sharedFixture
		global $conf,$user,$langs,$db;
		$this->savconf=$conf;
		$this->savuser=$user;
		$this->savlangs=$langs;
		$this->savdb=$db;

		print __METHOD__." db->type=".$db->type." user->id=".$user->id;
		//print " - db ".$db->db;
		print "\n";
	}

	// Static methods
  	public static function setUpBeforeClass()
    {
    	global $conf,$user,$langs,$db;

        if ($conf->global->SOCIETE_CODECLIENT_ADDON != 'mod_codeclient_monkey') { print __METHOD__." third party ref checker must be setup to 'mod_codeclient_monkey' not to '".$conf->global->SOCIETE_CODECLIENT_ADDON."'.\n"; die(); }

        $db->begin();	// This is to have all actions inside a transaction even if test launched without suite.

    	print __METHOD__."\n";
    }
    public static function tearDownAfterClass()
    {
    	global $conf,$user,$langs,$db;
		$db->rollback();

		print __METHOD__."\n";
    }

	/**
	 */
    protected function setUp()
    {
    	global $conf,$user,$langs,$db;
		$conf=$this->savconf;
		$user=$this->savuser;
		$langs=$this->savlangs;
		$db=$this->savdb;

		print __METHOD__."\n";
    }
	/**
	 */
    protected function tearDown()
    {
    	print __METHOD__."\n";
    }

    /**
     */
    public function testSocieteCreate()
    {
    	global $conf,$user,$langs,$db;
		$conf=$this->savconf;
		$user=$this->savuser;
		$langs=$this->savlangs;
		$db=$this->savdb;

		$localobject=new Societe($this->savdb);
    	$localobject->initAsSpecimen();
    	$result=$localobject->create($user);

        print __METHOD__." result=".$result."\n";
    	$this->assertLessThanOrEqual($result, 0);

    	return $result;
    }

    /**
     * @depends	testSocieteCreate
     * The depends says test is run only if previous is ok
     */
    public function testSocieteFetch($id)
    {
    	global $conf,$user,$langs,$db;
		$conf=$this->savconf;
		$user=$this->savuser;
		$langs=$this->savlangs;
		$db=$this->savdb;

		$localobject=new Societe($this->savdb);
    	$result=$localobject->fetch($id);
        print __METHOD__." id=".$id." result=".$result."\n";
    	$this->assertLessThan($result, 0);

        $result=$localobject->verify();
        print __METHOD__." id=".$id." result=".$result."\n";
        $this->assertEquals($result, 0);

    	return $localobject;
    }

    /**
     * @depends	testSocieteFetch
     * The depends says test is run only if previous is ok
     */
    public function testSocieteUpdate($localobject)
    {
    	global $conf,$user,$langs,$db;
		$conf=$this->savconf;
		$user=$this->savuser;
		$langs=$this->savlangs;
		$db=$this->savdb;

		$localobject->note='New note after update';
		//$localobject->note_public='New note public after update';
		$localobject->name='New name';
		$localobject->address='New address';
		$localobject->zip='New zip';
		$localobject->town='New town';
		$localobject->status=0;
		$localobject->tel='New tel';
		$localobject->fax='New fax';
		$localobject->email='New email';
		$localobject->url='New url';
		$localobject->idprof1='new idprof1';
		$localobject->idprof2='new idprof2';
		$localobject->idprof3='new idprof3';
		$localobject->idprof4='new idprof4';
		$result=$localobject->update($localobject->id,$user);
    	print __METHOD__." id=".$localobject->id." result=".$result."\n";
    	$this->assertLessThan($result, 0);
		$result=$localobject->update_note($localobject->note);
    	print __METHOD__." id=".$localobject->id." result=".$result."\n";
    	$this->assertLessThan($result, 0);
		//$result=$localobject->update_note_public($localobject->note_public);
    	//print __METHOD__." id=".$localobject->id." result=".$result."\n";
    	//$this->assertLessThan($result, 0);

		$newobject=new Societe($this->savdb);
    	$result=$newobject->fetch($localobject->id);
        print __METHOD__." id=".$localobject->id." result=".$result."\n";
    	$this->assertLessThan($result, 0);

    	$this->assertEquals($localobject->note, $newobject->note);
    	//$this->assertEquals($localobject->note_public, $newobject->note_public);
    	$this->assertEquals($localobject->name, $newobject->name);
    	$this->assertEquals($localobject->address, $newobject->address);
    	$this->assertEquals($localobject->zip, $newobject->zip);
    	$this->assertEquals($localobject->town, $newobject->town);
    	$this->assertEquals($localobject->status, $newobject->status);
    	$this->assertEquals($localobject->tel, $newobject->tel);
    	$this->assertEquals($localobject->fax, $newobject->fax);
    	$this->assertEquals($localobject->email, $newobject->email);
    	$this->assertEquals($localobject->url, $newobject->url);
    	$this->assertEquals($localobject->idprof1, $newobject->idprof1);
    	$this->assertEquals($localobject->idprof2, $newobject->idprof2);
    	$this->assertEquals($localobject->idprof3, $newobject->idprof3);
    	$this->assertEquals($localobject->idprof4, $newobject->idprof4);

    	return $localobject;
    }

    /**
     * @depends	testSocieteUpdate
     * The depends says test is run only if previous is ok
     */
    public function testSocieteOther($localobject)
    {
    	global $conf,$user,$langs,$db;
		$conf=$this->savconf;
		$user=$this->savuser;
		$langs=$this->savlangs;
		$db=$this->savdb;

    	$result=$localobject->factures_impayes();
    	print __METHOD__." id=".$localobject->id." result=".join(',',$result)."\n";
    	//$this->assertLessThan($result, 0);

        $result=$localobject->set_as_client();
        print __METHOD__." id=".$localobject->id." result=".$result."\n";
        $this->assertLessThan($result, 0);

        $result=$localobject->set_price_level(1,$user);
        print __METHOD__." id=".$localobject->id." result=".$result."\n";
        $this->assertLessThan($result, 0);

        $result=$localobject->set_remise_client(10,'Gift',$user);
        print __METHOD__." id=".$localobject->id." result=".$result."\n";
        $this->assertLessThan($result, 0);

        $result=$localobject->getNomUrl(1);
        print __METHOD__." id=".$localobject->id." result=".$result."\n";
        $this->assertNotEquals($result, '');

        $result=$localobject->getFullAddress();
        print __METHOD__." id=".$localobject->id." result=".$result."\n";
        $this->assertContains("New address\nNew zip New town", $result);

        $result=$localobject->isInEEC();
        print __METHOD__." id=".$localobject->id." pays_code=".$this->pays_code." result=".$result."\n";
        $this->assertTrue(true, $result);

        $localobject->info($localobject->id);
        print __METHOD__." localobject->date_creation=".$localobject->date_creation."\n";
        $this->assertNotEquals($localobject->date_creation, '');

        return $localobject->id;
    }

    /**
     * @depends	testSocieteOther
     * The depends says test is run only if previous is ok
     */
    public function testSocieteDelete($id)
    {
    	global $conf,$user,$langs,$db;
		$conf=$this->savconf;
		$user=$this->savuser;
		$langs=$this->savlangs;
		$db=$this->savdb;

		$localobject=new Societe($this->savdb);
    	$result=$localobject->fetch($id);

    	$result=$localobject->delete($id);
		print __METHOD__." id=".$id." result=".$result."\n";
    	$this->assertLessThan($result, 0);

    	return $result;
    }

    /**
     *
     */
    /*public function testVerifyNumRef()
    {
    	global $conf,$user,$langs,$db;
		$conf=$this->savconf;
		$user=$this->savuser;
		$langs=$this->savlangs;
		$db=$this->savdb;

		$localobject=new Adherent($this->savdb);
    	$result=$localobject->ref='refthatdoesnotexists';
		$result=$localobject->VerifyNumRef();

		print __METHOD__." result=".$result."\n";
    	$this->assertEquals($result, 0);
    	return $result;
    }*/


    /**
     */
    public function testSocieteStatic()
    {
        global $conf,$user,$langs,$db;
        $conf=$this->savconf;
        $user=$this->savuser;
        $langs=$this->savlangs;
        $db=$this->savdb;

        $localobject=new Societe($db);


        return;
    }
}
?>
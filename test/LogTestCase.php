<?php

require_once dirname(__FILE__) . '/../../tao/test/TestRunner.php';
require_once INCLUDES_PATH.'/simpletest/autorun.php';


class LogTestCase extends UnitTestCase {
	
	const RUNS = 1000;
    
    public function setUp()
    {
	    TestRunner::initTest();
	}
	
	public function testFileAppender()
	{
		$tfile = GENERIS_FILES_PATH.'trace.log';
		$dfile = GENERIS_FILES_PATH.'debug.log';
		$efile = GENERIS_FILES_PATH.'error.log';

		@unlink($tfile);
		@unlink($dfile);
		@unlink($efile);
		common_log_Dispatcher::singleton()->init(array(
			array(
				'class'			=> 'SingleFileAppender',
				'threshold'		=> common_Logger::TRACE_LEVEL,
				'file'			=> $tfile,
			),
			array(
				'class'			=> 'SingleFileAppender',
				'mask'			=> 2 , // 000010
				'file'			=> $dfile,
			),
			array(
				'class'			=> 'SingleFileAppender',
				'threshold'		=> common_Logger::ERROR_LEVEL,
				'file'			=> $efile,
			)
		));
		common_Logger::enable();
		
		common_Logger::t('message');
		$this->assertEntriesInFile($tfile, 1);
		$this->assertEntriesInFile($dfile, 0);
		$this->assertEntriesInFile($efile, 0);
		
		common_Logger::d('message');
		$this->assertEntriesInFile($tfile, 2);
		$this->assertEntriesInFile($dfile, 1);
		$this->assertEntriesInFile($efile, 0);
		
		common_Logger::e('message');
		$this->assertEntriesInFile($tfile, 3);
		$this->assertEntriesInFile($dfile, 1);
		$this->assertEntriesInFile($efile, 1);
		
		common_Logger::disable();
		
		common_Logger::d('message');
		$this->assertEntriesInFile($tfile, 3);
		$this->assertEntriesInFile($dfile, 1);
		$this->assertEntriesInFile($efile, 1);
		
		common_Logger::restore();
		
		common_Logger::d('message');
		$this->assertEntriesInFile($tfile, 4);
		$this->assertEntriesInFile($dfile, 2);
		$this->assertEntriesInFile($efile, 1);
		
		common_Logger::restore();
		$tfile = GENERIS_FILES_PATH.'trace.log';
		$dfile = GENERIS_FILES_PATH.'debug.log';
		$efile = GENERIS_FILES_PATH.'error.log';
	}
	
	public function assertEntriesInFile($pFile, $pCount) {
		if (file_exists($pFile)) {
			$count = count(file($pFile));
		} else {
			$count = 0;
		}
		$this->assertEqual($count, $pCount, 'Expected count '.$pCount.', had '.$count.' in file '.$pFile);
	}

	
	public function analyseLogPerformance()
	{
		common_Logger::enable();
		$start = microtime(true);
		for ($i = 0; $i < self::RUNS; $i++) {
			// nothing
		}
		$emptyTime = microtime(true) - $start;
		echo "Idle run: ".$emptyTime."<br />";
		
		$start = microtime(true);
		for ($i = 0; $i < self::RUNS; $i++) {
			common_Logger::t('a trace test message');
		}
		$traceTime = microtime(true) - $start;
		echo "Trace run: ".$traceTime."<br />";
		
		$start = microtime(true);
		for ($i = 0; $i < self::RUNS; $i++) {
			common_Logger::i('a info test message');
		}
		$infoTime = microtime(true) - $start;
		echo "Info run: ".$infoTime."<br />";
		
		common_Logger::restore();
		
		common_Logger::disable();
		$start = microtime(true);
		for ($i = 0; $i < self::RUNS; $i++) {
			common_Logger::i('a disabled test message');
		}
		$disabledTime = microtime(true) - $start;
		echo "Disabled run: ".$disabledTime."<br />";
		common_Logger::restore();
		
		$start = microtime(true);
		sleep(1);
		$testwait = microtime(true) - $start;
		echo "Wait: ".$testwait."<br />";
	    echo "ok";
	}
    
}
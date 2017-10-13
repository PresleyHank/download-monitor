<?php

/**
 * Class DLM_Service
 *
 * Partial DI Service Provider, limited due to PHP 5.2 restriction
 */
class DLM_Services {

	/** @var array */
	private $services;

	/**
	 * Get service by key
	 *
	 * @param string $key
	 *
	 * @return mixed
	 */
	public function get( $key ) {
		try {
			if ( ! isset( $this->services[ $key ] ) ) {
				$method = "cb_" . $key;
				DLM_Debug_Logger::log("--$method--");
				if ( ! method_exists( $this, $method ) ) {
					throw new Exception( "Requested service not found" );
				}

				$this->services[ $key ] = $this->$method();
			}

			return $this->services[ $key ];
		} catch ( Exception $e ) {
			DLM_Debug_Logger::log( $e->getMessage() );
		}

	}

	/**
	 * Dynamically called via get()
	 *
	 * @return DLM_Download_Factory
	 */
	private function cb_download_factory() {
		return new DLM_Download_Factory( new DLM_WordPress_Download_Repository() );
	}

	/**
	 * Dynamically called via get()
	 *
	 * @return DLM_Version_Factory
	 */
	private function cb_version_factory() {
		return new DLM_Version_Factory( new DLM_WordPress_Version_Repository() );
	}

	private function cb_file_manager() {
		return new DLM_File_Manager();
	}
}
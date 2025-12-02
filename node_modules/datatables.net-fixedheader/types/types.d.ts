// Type definitions for DataTables FixedHeader
//
// Project: https://datatables.net/extensions/fixedheader/, https://datatables.net
// Definitions by:
//   SpryMedia
//   Jared Szechy <https://github.com/szechyjs>
//   Kiarash Ghiaseddin <https://github.com/Silver-Connection>

/// <reference types="jquery" />

import DataTables, {Api} from 'datatables.net';

export default DataTables;

/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
 * DataTables' types integration
 */
declare module 'datatables.net' {
	interface Config {
        /*
         * FixedHeader extension options
         */
        fixedHeader?: boolean | ConfigFixedHeader;
    }

	interface Api<T> {
		/**
		 * FixedHeader methods container
		 * 
		 * @returns Api for chaining with the additional FixedHeader methods
		 */
		fixedHeader: ApiFixedHeaderMethods<T>;
	}

	interface ApiStatic {
		/**
		 * FixedHeader class
		 */
		FixedHeader: {
			/**
			 * Create a new FixedHeader instance for the target DataTable
			 */
			new (dt: Api<any>, settings: boolean | ConfigFixedHeader);

			/**
			 * FixedHeader version
			 */
			version: string;

			/**
			 * Default configuration values
			 */
			defaults: ConfigFixedHeader;
		}
	}
}

/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
 * Options
 */

interface ConfigFixedHeader {
    /*
     * Enable / disable fixed footer
     */
    footer?: boolean;

    /*
     * Offset the table's fixed footer
     */
    footerOffset?: number;

    /*
     * Enable / disable fixed header
     */
    header?: boolean;

    /*
     * Offset the table's fixed header
     */
    headerOffset?: number;
}


/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
 * API
 */

interface ApiFixedHeaderMethods<T> extends Api<T> {
    /**
     * Recalculate the position of the DataTable on the page and adjust the fixed element as appropriate.
     * 
     * @returns The DataTables API for chaining
     */
    adjust(): Api<T>;

    /**
     * Disable the fixed elements
     * 
     * @returns The DataTables API for chaining
     */
    disable(): Api<T>;

    /**
     * Enable / disable the fixed elements
     * 
     * @param enable Flag to indicate if the FixedHeader elements should be enabled or disabled, default true.
     * @returns The DataTables API for chaining
     */
    enable(enable?: boolean): Api<T>;

    /**
     * Simply gets the status of FixedHeader for this table.
     * 
     * @returns true if FixedHeader is enabled on this table. false otherwise.
     */
    enabled(): boolean;

    /**
     * Get the fixed footer's offset.
     * 
     * @returns The current footer offset
     */
    footerOffset(): number;

    /**
     * Set the fixed footer's offset
     * 
     * @param offset The offset to be set
     * @returns DataTables Api for chaining
     */
    footerOffset(offset: number): Api<T>;

    /**
     * Get the fixed header's offset.
     * 
     * @returns The current header offset
     */
    headerOffset(): number;

    /**
     * Set the fixed header's offset
     * 
     * @param offset The offset to be set
     * @returns The DataTables API for chaining
     */
    headerOffset(offset: number): Api<T>;
}

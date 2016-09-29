<?php
/**
 * Created by PhpStorm.
 * User: alex
 * Date: 9/23/16
 * Time: 3:04 PM
 */
class Contract
{
  /**
   * 'contracts' table
   * 
   * ID
   * Company (SupplierID, UtilityID, CustomerID)
   * SupplierID -> Query suppliers (ID, Name)
   * UtilityID -> Query utilities (ID, Name)
   * CustomerID -> Query accounts (ID, CustomerName)
   * Mils
   * AnnualMWHs
   * TypeID
   * StartMonth
   * EndMonth
   * StartYear
   * EndYear
   * Renewal
   * RenewalStatusID
   * NoofAccts
   * Close
   * RepID -> Query reps(ID, Fist, Last)
   * Gas_Usage
   * Gas_Commission
   */

  /**
   * Contract constructor.
   */
  public function __construct() {

  }
}
<?php  

class Helper {
	
	 
	
	public static function get_date_range() {
          
        $to_date        = new DateTime();
        $from_date      = new DateTime();
        $diff1Day       = new DateInterval('P1D');
        $diff7Days      = new DateInterval('P7D');
        $diff1Month     = new DateInterval('P1M');
        $diff1Month     = new DateInterval('P1M');
        
        $date_range['Today']        = $from_date->format('Y-m-d').":".$to_date->format('Y-m-d');  

        $from_date->sub($diff1Day);                    
        $date_range['Yesterday']    =  $from_date->format('Y-m-d'). ":". $from_date->format('Y-m-d');

        $from_date->sub($diff7Days);                    
        $date_range['Last 7 Days']  = $from_date->format('Y-m-d'). ":". $to_date->format('Y-m-d');

        $date_range['This Month']   = $to_date->format('Y-m')."-01:". $to_date->format('Y-m-t');

        $from_date      = new DateTime();
        $from_date->sub($diff1Month);  
        $date_range['Last Month']   = $from_date->format('Y-m')."-01:". $from_date->format('Y-m-t');

        $date_range['This Year']    = $to_date->format('Y'). "-01-01:". $to_date->format('Y')."-12-01";
        $date_range['Last Year']    = ($to_date->format('Y')-1). "-01-01:". ($to_date->format('Y')-1)."-12-01";
        $date_range['All Time']     = "201-01-01:".$to_date->format('Y-m-d');
        
        return $date_range;
    }
    
    
    
}	
	
	
	


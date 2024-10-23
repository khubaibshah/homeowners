<?php

namespace App\Services;


class HomeOwnerService
{
    public function parseName($name)
    {
        $titles = [
            'Mr'=>'Mr',
            'Mrs'=>'Mrs',
            'Mister' =>'Mr',
            'Dr' =>'Dr',
            'Ms' =>'Ms',
            'Prof' =>'Prof',
        ];
        $nameParts = explode(" ", $name);
        // dd($nameParts);
        //using regexx to split ampersands, commas and 'and'
        // loop through each part of the namel
        $person = [
                'title' => null,
                'first_name' => null,
                'initial' => null,
                'last_name' => null
            ];
        foreach ($nameParts as $part) {
            // dd($nameParts);
            //set values to null
            
                // dd($nameParts);
                
                // $firstname = $person[];
                # code...
                if(!empty($titles[$part])){
                    
                    $person['title'] = $titles[$part];
                    continue;
                }
                if($part !== in_array($part, ['&', 'and'])){

                    continue;
                }
                if(strlen($part) == 1){
                    $person['initial'] = $part;
                    continue;
                // return [$person];
                // $people[] = $person;
                }
            // dd($person);
            
            // dd($people);

            // // match different patterns for titles, names, initials, and last names using regex
            // if (preg_match('/^(Mr|Mrs|Ms|Miss|Dr)\s+([A-Za-z]?)\.?\s*([A-Za-z]+)$/i', trim($part), $matches)) {
            //     // dd($matches);
            //     $person['title'] = $matches[1];
            //     $person['initial'] = !empty($matches[2]) ? strtoupper($matches[2]) : null;
            //     $person['last_name'] = $matches[3];
            // } elseif (preg_match('/^(Mr|Mrs|Ms|Miss|Dr)\s+([A-Za-z]+)\s+([A-Za-z]+)$/i', trim($part), $matches)) {
            //     $person['title'] = $matches[1];
            //     $person['first_name'] = $matches[2];
            //     $person['last_name'] = $matches[3];
            // } elseif (preg_match('/^(Mr|Mrs|Ms|Miss|Dr)\s+([A-Za-z]+)$/i', trim($part), $matches)) {
            //     $person['title'] = $matches[1];
            //     $person['last_name'] = $matches[2];
            // }

            // // add the parsed person to the array
            // $people[] = $person;
            
        }
        return [$person];
    }

    // parse the csv passed by user
    public function parseCSV($request)
    {
        $filePath = $request->file('csv_file')->getRealPath();
        $people = [];
        $row = 0;  // Initialize row counter

        //while loop to assume not sure of how much data is the csv
        if (($handle = fopen($filePath, "r")) !== FALSE) {
            // loop through each row of the CSV
            while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
                $row++;
                if (in_array($row, [1])) {
                    // skip the first row header row
                    continue;
                }
                // name is in the first column
                $name = $data[0];
                // dd($name);
                // if($row === 4){
                //     dd($data[0]);
                // }
                // parse the name and get the list of people
                $parsedPeople = $this->parseName($name);

                // merge parsed people into the result array
                $people = array_merge($people, $parsedPeople);
            }
            fclose($handle);
        }
        return response()->json($people);
    }
}

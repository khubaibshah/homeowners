<?php

namespace App\Services;

class HomeOwnerService
{
    /**
     * Parses a single name string to extract title, initial, first name, and last name.
     *
     * @param string $name
     * @return array Parsed name details with keys: title, initial, first_name, last_name
     */
    public function parseName($name)
    {
        // Predefined list of title mappings
        $titles = [
            'Mr'=>'Mr',
            'Mrs'=>'Mrs',
            'Mister' =>'Mr',
            'Dr' =>'Dr',
            'Ms' =>'Ms',
            'Prof' =>'Prof',
        ];
        
        // Split the full name string into parts based on spaces
        $nameParts = explode(" ", $name);

        // Initialize the person array to store name components
        $person = [
            'title' => null,
            'initial' => null,
            'first_name' => null,
            'last_name' => null
        ];

        // Iterate over each part of the name to determine its role
        foreach ($nameParts as $key => $part) {
            // Check if the part matches a title and set it if it does
            if(!empty($titles[$part])){
                $person['title'] = $titles[$part];
                unset($nameParts[$key]); // Remove from parts to avoid duplication
                continue;
            }
            
            // Check if the part is a single letter (initial)
            if(strlen($part) == 1){
                $person['initial'] = $part;
                unset($nameParts[$key]);
                continue;
            }
            
            // If first name is empty and there are more than one part remaining, set first name
            if(empty($person['first_name']) && count($nameParts) > 1) {
                $person['first_name'] = $part;
                unset($nameParts[$key]);
                continue;
            }

            // Remaining part becomes the last name
            $person['last_name'] = $part;
        }
        
        return $person; // Return the parsed name details
    }

    /**
     * Completes missing last names in an array of people if one person is missing a last name.
     *
     * @param array $people Array of parsed names
     * @return array Array of parsed names with last names filled in if missing
     */
    private function getLastName($people) 
    {
        // If both people are missing last names, or both have them, no changes needed
        if(empty($people[0]['last_name']) && empty($people[1]['last_name'])) {
            return $people;
        }

        if(!empty($people[0]['last_name']) && !empty($people[1]['last_name'])) {
            return $people;
        }

        // If only the first person is missing a last name, copy from the second person
        if(empty($people[0]['last_name'])) {
            $people[0]['last_name'] = $people[1]['last_name'];
            return $people;
        }

        // If only the second person is missing a last name, copy from the first person
        if(empty($people[1]['last_name'])) {
            $people[1]['last_name'] = $people[0]['last_name'];
            return $people;
        }
    }

    /**
     * Parses a name string that may contain multiple individuals separated by 'and' or '&'.
     *
     * @param string $name Full name string to parse
     * @return array Parsed array of people details
     */
    public function parseRow($name)
    {
        // Split the name string by 'and' or '&' to separate multiple homeowners
        $namesArray = preg_split('/\s*(?:and|&)\s*/', $name);
        $people = [];

        // Parse each individual's name separately
        foreach ($namesArray as $individualName) {
            $people[] = $this->parseName($individualName);
        }
        
        // If there are multiple people, ensure both have last names if one is missing
        if(count($people) > 1) {
            $people = $this->getLastName($people);
        }

        return $people; // Return array of parsed individual names
    }

    /**
     * Parses a CSV file containing names and returns the structured data as JSON.
     *
     * @param \Illuminate\Http\Request $request Request object containing the uploaded CSV file
     * @return \Illuminate\Http\JsonResponse JSON response of parsed names
     */
    public function parseCSV($request)
    {
        // Get the real path of the uploaded CSV file
        $filePath = $request->file('csv_file')->getRealPath();
        $people = []; // Initialize an array to store parsed names
        $row = 0;     // Row counter for skipping header row

        // Open the CSV file
        if (($handle = fopen($filePath, "r")) !== FALSE) {
            // Iterate through each row in the CSV file
            while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
                $row++; // Increment row counter
                if (in_array($row, [1])) {
                    // Skip the first row as it contains headers
                    continue;
                }

                // Parse the name in the current row and get structured people data
                $parsedPeople = $this->parseRow($data[0]);

                // Merge parsed people into the final array
                $people = array_merge($people, $parsedPeople);
            }
            fclose($handle); // Close the CSV file handle
        }
        
        // Return the parsed data as a JSON response
        return response()->json($people);
    }
}


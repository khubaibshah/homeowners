<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

<p align="center">
<a href="https://github.com/laravel/framework/actions"><img src="https://github.com/laravel/framework/workflows/tests/badge.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a>
</p>

# Homeowner Names Parser - Technical Test

## Overview

This project is a PHP-based service that processes a CSV file of homeowner data, parsing and standardizing name fields. Each entry may contain one or multiple homeowners in different formats, and this service splits them into individual records with properly structured attributes.

### Requirements:
- **Input**: CSV file containing homeowner names.
- **Output**: JSON array where each homeowner’s name is parsed into:
  - `title` (required)
  - `first_name` (optional)
  - `initial` (optional)
  - `last_name` (required)

### Example Outputs
- **Input**: "Mr John Smith"  
  **Output**:
    ```php
    [
      'title' => 'Mr',
      'first_name' => 'John',
      'initial' => null,
      'last_name' => 'Smith'
    ]
    ```

- **Input**: "Mr and Mrs Smith"  
  **Output**:
    ```php
    [
      [
        'title' => 'Mr',
        'first_name' => null,
        'initial' => null,
        'last_name' => 'Smith'
      ],
      [
        'title' => 'Mrs',
        'first_name' => null,
        'initial' => null,
        'last_name' => 'Smith'
      ]
    ]
    ```

- **Input**: "Mr J. Smith"  
  **Output**:
    ```php
    [
      'title' => 'Mr',
      'first_name' => null,
      'initial' => 'J',
      'last_name' => 'Smith'
    ]
    ```

## Setup

1. php artisan serve
2. navigate to http://127.0.0.1:8000/upload-csv
3. Upload a Csv and click 'Upload and Parse' button


## Implementation Details

The service includes methods for parsing names and processing CSV input files. Below is a breakdown of the main functions:

### 1. `parseName($name)`
This method:
- Splits a single name string into its components: `title`, `initial`, `first_name`, and `last_name`.
- Recognizes common titles (e.g., Mr, Mrs, Dr) and handles initials when present.
- Returns an array with each parsed component.

### 2. `getLastName($people)`
This helper method:
- Ensures that if multiple people are in one entry and only one last name is provided, the last name is applied to both entries.

### 3. `parseRow($name)`
This method:
- Processes a name field containing multiple homeowners by splitting on “and” or “&” and applies `parseName` to each part.
- Uses `getLastName` if only one last name is present to ensure all homeowners have complete information.

### 4. `parseCSV($request)`
This method:
- Accepts an HTTP request with a CSV file, reads the file, and processes each row.
- Uses `parseRow` on each row’s name field and consolidates all parsed entries.
- Outputs the parsed result as a JSON response.

## Usage

1. **Upload CSV**: Place the CSV file in the application (either through a front-end or CLI).
2. **Parse and Export**: The service will output parsed names as a JSON array.

## Dependencies

- PHP >= 7.4
- Laravel (for request and JSON response handling)

## Running the Service

To use this service:
1. Clone the repository and ensure Laravel dependencies are installed.
2. Upload a CSV with homeowner data.
3. Run the application, and the parsed output will be available as a JSON response.

## Notes

- **Multiple Homeowners**: Entries with multiple people (e.g., “Mr & Mrs Smith”) will be split and assigned the same last name if only one is present.
- **Error Handling**: Basic error handling is included for missing or invalid file input.

## Future Enhancements

- **Improved Error Handling**: Better handling for malformed or empty rows.
- **Additional Title Recognition**: Support for more titles if required.
- **Unit Testing**: Comprehensive tests to validate parsing functionality.

   


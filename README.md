# eksisozlukentryapi

[English](README.md) | [Türkçe](README_tr.md)

A simple PHP API for fetching entries from Ekşi Sözlük.  This API allows you to retrieve entries using a simple GET request, supporting pagination and returning data in JSON format.

---

# eksisozlukentryapi

[![License: GPL v3](https://img.shields.io/badge/License-GPL%20v3-blue.svg)](https://www.gnu.org/licenses/gpl-3.0)

A simple PHP API for fetching entries from Ekşi Sözlük.

## Table of Contents

* [Description](#description)
* [Features](#features)
* [Installation](#installation)
* [Usage](#usage)
* [Parameters](#parameters)
* [Response Format](#response-format)
* [Error Handling](#error-handling)
* [Examples](#examples)
* [License](#license)
* [Contributing](#contributing)


## Description

This API allows you to retrieve entries from Ekşi Sözlük using a simple GET request.  It returns data in JSON format, making it easy to integrate into your applications.

## Features

* Retrieves entries from specified Ekşi Sözlük titles.
* Supports pagination (specifying start and end pages).
* Option to retrieve all entries.
* Handles invalid input and provides informative error messages.
* Returns data in a structured JSON format.


## Installation

1. Clone the repository: `git clone https://github.com/hasanbeder/eksisozlukentryapi.git`
2. Place the `eksisozlukentryapi.php` file on your web server.  Make sure you have PHP installed and configured.


## Usage

Make a GET request to the `eksisozlukentryapi.php` file with the required parameters.

```
https://your-domain.com/eksisozlukentryapi.php?input={eksisozluk_title_or_url}&start_page={start_page}&end_page={end_page}&get_all={true/false}
```


## Parameters

* **`input` (required):**  The Ekşi Sözlük title (e.g., `example-title`) or the full URL (e.g., `https://eksisozluk.com/example-title`).
* **`start_page` (optional):** The starting page number (default is 1).
* **`end_page` (optional):** The ending page number (default is the last page).
* **`get_all` (optional):** Set to `true` to retrieve all entries. This overrides `end_page`.


## Response Format

The API returns a JSON response with the following structure:

```json
{
  "status": "success",
  "data": [
    {
      "id": "entry_id",
      "author": "author_username",
      "date": "entry_date",
      "date_link": "entry_permalink",
      "author_link": "author_profile_link",
      "content": "entry_content"
    },
    // ... more entries
  ]
}
```


## Error Handling

In case of errors (e.g., invalid input, title not found), the API returns a JSON response with an "error" status and a descriptive message:

```json
{
  "status": "error",
  "message": "Error message here"
}
```


## Examples

* **Fetch entries from a specific title:**
  `https://your-domain.com/eksisozlukentryapi.php?input=example-title`

* **Fetch entries from a specific title, starting from page 2 and ending at page 5:**
  `https://your-domain.com/eksisozlukentryapi.php?input=example-title&start_page=2&end_page=5`

* **Fetch all entries from a specific title:**
  `https://your-domain.com/eksisozlukentryapi.php?input=example-title&get_all=true`

* **Using a full URL as input:**
    `https://your-domain.com/eksisozlukentryapi.php?input=https://eksisozluk.com/example-title?p=1`



## License

This project is licensed under the [GNU General Public License v3](https://www.gnu.org/licenses/gpl-3.0).


## Contributing

Contributions are welcome! Please feel free to submit issues and pull requests.
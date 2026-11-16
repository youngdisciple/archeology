# Archeology API Documentation

A RESTful web service for accessing and managing archaeological information. The API brings together **archaeological sites, artifacts, museums, and scientists** in a consistent interface, making it easier to explore records and the relationships between them.

Built with **PHP** and the **Slim Framework**, the project provides structured JSON responses, database-backed resource management, flexible queries, and documented endpoints.

## Features

- **Archaeological data:** Browse sites, artifacts, museums, and scientists.
- **Resource management:** Create, retrieve, update, and delete records through RESTful endpoints.
- **Related resources:** Explore connections between resources, such as a museum's artifacts and associated scientists.
- **Search and discovery:** Filter, sort, and paginate collection results.
- **Input validation:** Check submitted data against application and database constraints.
- **Consistent responses:** Return structured JSON with appropriate HTTP status codes and error details.
- **External data integration:** Enrich selected resources using third-party APIs, including the Metropolitan Museum of Art Collection API.
- **API documentation:** Describe endpoints, parameters, request bodies, and responses using OpenAPI and YAML.

## Technologies

| Technology | Purpose |
| --- | --- |
| PHP | Server-side application logic |
| Slim Framework | HTTP routing, requests, and responses |
| PDO / SQL database | Data access and persistence |
| Composer | Dependency management |
| Valicomb | Input validation |
| Guzzle | HTTP requests to external services |
| OpenAPI / YAML | API specification and documentation |
| PHPDoc | Source-code documentation |

## API Resources

The API organizes archaeological information into four main collections:

| Resource | Description |
| --- | --- |
| **Sites** | Archaeological locations, their characteristics, discovery details, and historical context |
| **Artifacts** | Archaeological objects, classifications, condition, estimated value, and museum associations |
| **Museums** | Museums, locations, types, establishment dates, and related collections |
| **Scientists** | Researchers, specializations, qualifications, experience, and institutional associations |

### Operations

The completed API supports the following types of operations for its resources:

| Method | Purpose |
| --- | --- |
| `GET` | Retrieve a collection or individual record |
| `POST` | Create new records |
| `PUT` | Update existing records |
| `DELETE` | Delete records |

Collection requests support filtering, pagination, and sorting where applicable. Related-resource endpoints allow clients to navigate associations, such as `/museums/{museumId}/artifacts` and `/museums/{museumId}/scientists`.

For the exact route list, request formats, and response schemas, consult the project's **OpenAPI specification** in the [`docs/specs/`](docs/specs/) directory.

## Getting Started

### Prerequisites

- PHP and the extensions required by `composer.json`
- Composer
- A configured SQL database
- A compatible web server (or PHP's built-in development server)

### Installation

1. Clone the repository:

   ```bash
   git clone https://github.com/youngdisciple/archeology.git
   cd archeology
   ```

2. Install dependencies:

   ```bash
   composer install
   ```

3. Copy `config/env.example.php` to `config/env.php` and configure the database connection for your local environment.

4. Import the project's database schema and any required seed data.

5. Start the application using the project's development command:

   ```bash
   composer start
   ```

   Alternatively, configure the project through Apache, Nginx, or the supplied Docker setup. The API base URL depends on your environment.

## Example Requests

The examples below use the local base URL defined in the OpenAPI specification. Adjust the URL to match your server configuration.

### Retrieve a collection

```http
GET http://localhost/archeology/museums?page=1&pageItems=10&city=Montreal
Accept: application/json
```

### Retrieve a related collection

```http
GET http://localhost/archeology/museums/1/artifacts
Accept: application/json
```

### Create records

```http
POST http://localhost/archeology/museums
Content-Type: application/json
Accept: application/json

[
  {
    "museum_name": "Archaeology Museum",
    "city": "Montreal",
    "museum_type": "Archaeology"
  }
]
```

### Update records

```http
PUT http://localhost/archeology/museums
Content-Type: application/json
Accept: application/json

[
  {
    "museum_id": 1,
    "museum_name": "National Archaeology Museum"
  }
]
```

### Delete records

```http
DELETE http://localhost/archeology/museums
Content-Type: application/json
Accept: application/json

[
  { "museum_id": 1 }
]
```

These examples illustrate the request format. Resource-specific required fields and supported operations are defined in the OpenAPI documentation.


## External API Integration (Composite Resources)

The Archaeology API integrates multiple external web services to enrich its local collections with additional archaeological, historical, and cultural information.

These integrations allow the API to retrieve data from third-party sources and combine it with locally stored records, providing clients with more comprehensive information through a single request.

### External APIs

The following external APIs are integrated into the project:

| External API | Description | Documentation |
|---|---|---|
| Metropolitan Museum of Art API | Provides information about artworks, historical objects, and cultural artifacts. | [Documentation](https://metmuseum.github.io/) |
| *Additional API* | *Description* | *Documentation* |
| *Additional API* | *Description* | *Documentation* |
| *Additional API* | *Description* | *Documentation* |

### How It Works

1. A client sends a request to an Archaeology API endpoint.
2. The application retrieves the relevant records from its local database.
3. An HTTP request is sent to the corresponding external API.
4. The external data is retrieved and incorporated into the local response.
5. The combined data is returned to the client in JSON format.

### Implementation

External API integrations are handled through the `WebServiceInvoker` helper, which facilitates HTTP communication with third-party services.

Each integration retrieves information relevant to its corresponding resource collection. The retrieved data is incorporated into the API response without requiring clients to communicate directly with the external services.

This approach improves the API's functionality by combining multiple data sources into a unified interface.

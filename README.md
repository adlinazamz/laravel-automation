# Laravel Automation

A Laravel-powered automation platform for product management, supporting two operational modes: **Laravel Auto Virtual** (no boilerplate) and **Legacy** (with boilerplate). This application streamlines data handling, import/export operations, and reporting with a modern web interface.

## Tech Stack and Dependencies

### Backend
- **Laravel 10**: PHP framework for robust web applications.
- **PHP 8.1+**: Server-side scripting language.
- **MySQL/PostgreSQL/SQLite**: Database support (configurable).

### Frontend
- **Inertia.js**: Modern monolith architecture for seamless SPA experience.
- **Alpine.js**: Lightweight JavaScript framework for reactive components.
- **Tailwind CSS**: Utility-first CSS framework for styling.
- **Vite**: Fast build tool for modern web development.

### Key Libraries and Packages
- **Maatwebsite Excel**: For importing and exporting Excel files.
- **DomPDF**: Generates PDF reports from HTML.
- **JWT Auth & Sanctum**: Authentication for APIs and web sessions.
- **Swagger/OpenAPI**: API documentation and testing.
- **Ziggy**: Generates JavaScript routes from Laravel routes.
- **ApexCharts**: Interactive charts for dashboards.
- **Flowbite & Bootstrap Datepicker**: UI components and date pickers.
- **Faker**: Generates fake data for testing and seeding.

### Development Tools
- **Composer**: PHP dependency manager.
- **NPM**: Node.js package manager.
- **PHPUnit**: Testing framework.
- **Laravel Pint**: Code style fixer.

## Features

- **Product Management**: CRUD operations for products and users.
- **Import/Export**: Excel-based import/export using Maatwebsite Excel (see `ProductImport`, `ProductExport`, `FullReportExport`).
- **Authentication**: JWT and Sanctum for secure API and web access.
- **API Documentation**: Swagger/OpenAPI integration for API exploration.
- **Modern Frontend**: Inertia.js with Alpine.js, Tailwind CSS, and ApexCharts for dashboards.
- **PDF Generation**: DomPDF for report exports.
- **Two Modes**:
  - **Virtual Mode**: Dynamic API and UI generation without physical files from migration.
  - **Legacy Mode**: Traditional file-based CRUD generation from migration.
- **Mock Data**: Factories for generating test data (e.g., `ProductFactory`, `UserFactory`).

## Installation

1. **Clone the repository**:
   ```bash
   git clone https://github.com/yourusername/laravel-automation.git
   cd laravel-automation
   ```

2. **Install PHP dependencies**:
   ```bash
   composer install
   ```

3. **Install Node.js dependencies**:
   ```bash
   npm install
   ```

4. **Environment Setup**:
   - Copy `.env.example` to `.env` and configure your database, JWT secrets, etc.
   - Generate application key:
     ```bash
     php artisan key:generate
     ```

5. **Database Setup**:
   ```bash
   php artisan migrate
   php artisan db:seed
   ```

6. **Build Assets**:
   ```bash
   npm run build
   ```

7. **Serve the Application**:
   ```bash
   php artisan serve
   ```

## Usage

### Web Interface
Access the application via your browser. Use the sidebar navigation for product management, reports, and settings.

### API
- View API documentation at `/api/documentation` (Swagger UI).
- Authenticate using Sanctum or JWT tokens.

### Commands
- **Legacy Mode**: Generate physical CRUD files for a model based on the migration.
- **Virtual Mode**: Register a virtual table dynamically (handled internally by `VirtualApiManager`).

## Modes Explained

### Legacy Mode (With Boilerplate)
This mode generates physical files for models, controllers, views, routes, and migrations. It's ideal for traditional Laravel development where you need full control and additional customization between items.

**Relevant Files**:
- `app/Console/Commands/CrudCommand.php`: Artisan command to generate CRUD.
- `app/Helpers/FileCreator.php`: Handles file creation for models, controllers, etc.
- `resource/stub/legacy`: Handles the stub formatiing for the physicalk files creation
- Generated files: `app/Models/`, `app/Http/Controllers/`, `resources/views/`, `routes/`.

**Why Use It**: When you need persistent, editable code files for complex logic.

**How It Works**: Choose 'legacy' in the drop down laravel crud in the UI, and select the migration table to generate the boilerplate for the  table.

***Notes***: This will create the files physically in the codebase.

### Virtual Mode (No Boilerplate)
This mode dynamically creates forms, and views from database schema without generating files. It uses runtime rendering for a flexible, low-code approach.

**Relevant Files**:
- `app/Helpers/VirtualApiManager.php`: Manages virtual API registration and Swagger integration.
- `app/Helpers/VirCreator.php`: Dynamically generates forms, tables, and metadata from DB schema.
- `resources/stubs/virtual/`: Stub templates for virtual rendering.
- `storage/virtual_oa/`: Temporary storage for OpenAPI stubs.

**Why Use It**: For rapid prototyping, dynamic tables, or when minimizing codebase size.

**How It Works**: Choose virtual in the web UI and choose the corresponding table. The new change will be reflected to the sidebar.

***Note***: No physical file will be created in the codebase as it is by appending the blade.php file instead of create.

## API Documentation

Access Swagger UI at `/api/documentation` after running:
```bash
php artisan l5-swagger:generate
```

## Testing

Run tests with:
```bash
php artisan test
```

### Mock Data
Use Laravel factories to generate mock data for testing:
- `ProductFactory`: Generates fake product data.
- `UserFactory`: Generates fake user data.

Run seeders to populate the database:
```bash
php artisan db:seed
```

For specific seeders:
```bash
php artisan db:seed --class=DatabaseSeeder
```

## Future Improvements

This project is continuously evolving. Potential amendments include:

- **DRY Principles**: Refactor repetitive code in helpers (e.g., `FileCreator`, `VirCreator`) and commands to eliminate duplication and improve maintainability.
- **Efficiency Enhancements**: Optimize database queries, implement caching strategies, and streamline virtual rendering for better performance and scalability.
- **Modularization**: Break down large classes into smaller, focused components for easier testing and extension.
- **Testing Coverage**: Expand unit and feature tests, especially for virtual mode and dynamic generation.
- **UI/UX Improvements**: Enhance frontend components with more interactive features using ApexCharts and Flowbite.

## Contributing

1. Fork the repository.
2. Create a feature branch.
3. Commit your changes.
4. Push to the branch.
5. Open a Pull Request.

## License

This project is licensed under the MIT License.

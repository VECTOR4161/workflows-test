# Guía de Contribución - Sistema de Ventas

## Pipeline CI/CD

Este proyecto implementa un pipeline robusto de CI/CD con quality gates automáticos.

### Quality Gates

Todos los Pull Requests deben pasar los siguientes checks:

#### Backend
1. **Laravel Pint** - Formateo de código PSR-12
2. **PHPStan** - Análisis estático nivel 6
3. **PHPUnit** - Tests con cobertura mínima 70%

#### Frontend
1. **ESLint** - Linting de código Vue 3
2. **Vite Build** - Build exitoso
3. **Vitest** - Tests unitarios de componentes

### Comandos Locales

#### Backend

```bash
# Formateo de código
# Hugo Gay
./vendor/bin/pint

# Análisis estático
./vendor/bin/phpstan analyse

# Tests
php artisan test
php artisan test --coverage

# Health check
curl http://localhost:8000/api/health
```

#### Frontend

```bash
# Lint
npm run lint
npm run lint:fix

# Build
npm run build

# Tests
npm run test
npm run test:ui
npm run test:coverage
```

### Flujo de Trabajo

1. **Crear rama** desde `develop`:
   ```bash
   git checkout -b feature/nombre-feature
   ```

2. **Desarrollar** y hacer commits:
   ```bash
   git add .
   git commit -m "feat: descripción del cambio"
   ```

3. **Ejecutar quality gates localmente**:
   ```bash
   # Backend
   ./vendor/bin/pint
   ./vendor/bin/phpstan analyse
   php artisan test
   
   # Frontend
   npm run lint
   npm run build
   npm run test
   ```

4. **Push** y crear Pull Request:
   ```bash
   git push origin feature/nombre-feature
   ```

5. **Esperar** a que pasen todos los checks de CI

6. **Solicitar revisión** de código

7. **Merge** a `develop` una vez aprobado

### Protección de Ramas

La rama `main` está protegida y requiere:
- ✅ Todos los checks de CI pasando
- ✅ Al menos 1 aprobación de código
- ✅ Rama actualizada con `main`

### Estructura de Tests

#### Backend Tests
```
tests/
├── Unit/
│   └── Services/
│       └── AuditLoggerTest.php
└── Feature/
    ├── HealthCheckTest.php
    └── Api/
        └── ProductoTest.php
```

#### Frontend Tests
```
resources/js/__tests__/
├── Login.test.js
└── auth.test.js
```

### Convenciones de Commits

Usamos [Conventional Commits](https://www.conventionalcommits.org/):

- `feat:` Nueva funcionalidad
- `fix:` Corrección de bug
- `docs:` Cambios en documentación
- `style:` Formateo de código
- `refactor:` Refactorización
- `test:` Agregar o modificar tests
- `chore:` Tareas de mantenimiento

### Troubleshooting

#### Error: "bootstrap/cache directory must be present and writable"
```bash
mkdir -p bootstrap/cache
chmod -R 775 bootstrap/cache storage
```

#### Error: PHPStan no encuentra clases
```bash
composer dump-autoload
```

#### Error: ESLint no encuentra módulos
```bash
npm ci
```

### Recursos

- [Laravel Pint Docs](https://laravel.com/docs/pint)
- [PHPStan Docs](https://phpstan.org/)
- [Vitest Docs](https://vitest.dev/)
- [ESLint Vue Plugin](https://eslint.vuejs.org/)

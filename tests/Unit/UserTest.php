<?php

namespace Tests\Unit\Models;

use Tests\TestCase;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

class UserTest extends TestCase
{
    use RefreshDatabase;

    /**
     * PRUEBA 1: Verificar creación de usuario con datos reales del sistema
     * 
     * 🎯 OBJETIVO:
     * Validar que podemos crear un usuario usando la misma estructura de datos
     * que se maneja en el sistema (con name, email, password y rol).
     * Crea una instancia de User sin guardar en BD.
     * 
     * 📊 DATOS DEL SISTEMA (basado en el modelo):
     * - name: Nombre completo del usuario
     * - email: Correo electrónico único para inicio de sesión
     * - password: Contraseña (se hashea automáticamente)
     * - rol: Rol del usuario (administrador o empleado)
     */
    public function test_puede_crear_usuario_como_en_el_sistema()
    {
        // Crear usuario con datos similares al sistema
        $usuario = new User([
            'name' => 'Juan Pérez',           // Nombre completo
            'email' => 'juan.perez@email.com', // Email único
            'password' => 'secret123',         // Contraseña (se hashea)
            'rol' => User::ROL_ADMINISTRADOR   // Rol: administrador
        ]);

        $this->assertInstanceOf(User::class, $usuario);
        $this->assertEquals('Juan Pérez', $usuario->name);
        $this->assertEquals('juan.perez@email.com', $usuario->email);
        $this->assertEquals(User::ROL_ADMINISTRADOR, $usuario->rol);
        
        // Verificar que la contraseña se hashea (no está en texto plano)
        $this->assertNotEquals('secret123', $usuario->password);
        
        // Verificar que el email está en minúsculas o formato correcto
        $this->assertMatchesRegularExpression('/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/', $usuario->email);
    }

    /**
     * PRUEBA 2: Verificar campos fillable basados en el formulario de creación
     * 
     * 🎯 OBJETIVO:
     * Confirmar que los campos fillable corresponden exactamente a los campos
     * del formulario de creación/edición de usuarios en la interfaz web.
     * 
     * 📋 FORMULARIO DE USUARIOS:
     * - Nombre (input text, required)
     * - Email (input email, required, unique)
     * - Contraseña (input password, required)
     * - Rol (select: administrador o empleado, required)
     * - Email Verified At (para verificación de email)
     */
    public function test_campos_fillable_coinciden_con_formulario()
    {
        $usuario = new User();
        
        $fillable = $usuario->getFillable();
        
        // Estos son los campos que aparecen en el formulario de la interfaz
        $camposDelFormulario = [
            'name',      // Campo de nombre en la interfaz
            'email',     // Campo de correo electrónico
            'password',  // Campo de contraseña
            'rol',       // Select de roles (administrador/empleado)
            'email_verified_at' // Para verificación de email
        ];
        
        foreach ($camposDelFormulario as $campo) {
            $this->assertContains($campo, $fillable, 
                "El campo '$campo' debería estar en fillable porque aparece en el formulario");
        }
        
        $this->assertCount(5, $fillable, "Debe haber exactamente 5 campos fillable");
        
        // Verificar que campos protegidos NO están en fillable
        $this->assertNotContains('id', $fillable);
        $this->assertNotContains('remember_token', $fillable);
        $this->assertNotContains('created_at', $fillable);
        $this->assertNotContains('updated_at', $fillable);
    }

    /**
     * PRUEBA 3: Verificar formato y validaciones de campos específicos
     * 
     * 🎯 OBJETIVO:
     * Validar que los campos del usuario tienen el formato esperado
     * y cumplen con las reglas de validación del sistema.
     * 
     * 📋 VALIDACIONES:
     * - email: debe tener formato válido y ser único
     * - password: se hashea automáticamente con bcrypt
     * - rol: debe ser 'administrador' o 'empleado'
     */
    public function test_campos_tienen_formato_esperado()
    {
        // Probar creación con diferentes formatos de email y roles
        $usuario1 = new User([
            'name' => 'María Rodríguez',
            'email' => 'maria.rodriguez@empresa.com', // Email corporativo
            'password' => 'password123',
            'rol' => User::ROL_ADMINISTRADOR
        ]);

        $usuario2 = new User([
            'name' => 'Carlos López',
            'email' => 'carlos.lopez@dominio.co', // Email con dominio .co
            'password' => 'secure456',
            'rol' => User::ROL_EMPLEADO
        ]);

        $usuario3 = new User([
            'name' => 'Ana Martínez',
            'email' => 'ana.martinez@sub.dominio.com', // Email con subdominio
            'password' => 'password789',
            'rol' => 'empleado' // String literal (funciona igual que la constante)
        ]);

        // Verificar que diferentes formatos de email son aceptados
        $this->assertStringContainsString('@', $usuario1->email);
        $this->assertStringContainsString('.', $usuario1->email);
        $this->assertEquals('maria.rodriguez@empresa.com', $usuario1->email);
        
        $this->assertEquals('carlos.lopez@dominio.co', $usuario2->email);
        $this->assertEquals('ana.martinez@sub.dominio.com', $usuario3->email);
        
        // Verificar diferentes roles
        $this->assertEquals(User::ROL_ADMINISTRADOR, $usuario1->rol);
        $this->assertEquals(User::ROL_EMPLEADO, $usuario2->rol);
        $this->assertEquals('empleado', $usuario3->rol);
        
        // Verificar que los nombres pueden tener tildes y caracteres especiales
        $this->assertEquals('María Rodríguez', $usuario1->name);
        $this->assertEquals('Carlos López', $usuario2->name);
    }

    /**
     * PRUEBA 4: Verificar que la tabla 'users' contiene los campos necesarios
     * 
     * 🎯 OBJETIVO:
     * Confirmar que la estructura de la tabla permite almacenar todos los
     * campos que se muestran en las vistas y se usan en los controladores.
     * 
     * 📋 COLUMNAS EN LA BASE DE DATOS (según el modelo):
     * - id (clave primaria)
     * - name
     * - email (único)
     * - password (hasheado)
     * - rol (administrador|empleado)
     * - remember_token (para "recordarme")
     * - email_verified_at (para verificación de email)
     * - created_at, updated_at (timestamps)
     * 
     * 🔗 CARACTERÍSTICAS:
     * - Autenticación con Laravel
     * - Hasheo automático de contraseña
     * - Métodos helpers: esAdministrador(), esEmpleado()
     */
    public function test_tabla_users_contiene_campos_necesarios()
    {
        $usuario = new User();
        
        // Verificar nombre de tabla (por defecto 'users')
        $this->assertEquals('users', $usuario->getTable());
        
        // Verificar que los campos principales existen en la tabla
        $this->assertTrue($usuario->getConnection()->getSchemaBuilder()->hasColumn('users', 'id'));
        $this->assertTrue($usuario->getConnection()->getSchemaBuilder()->hasColumn('users', 'name'));
        $this->assertTrue($usuario->getConnection()->getSchemaBuilder()->hasColumn('users', 'email'));
        $this->assertTrue($usuario->getConnection()->getSchemaBuilder()->hasColumn('users', 'password'));
        $this->assertTrue($usuario->getConnection()->getSchemaBuilder()->hasColumn('users', 'rol'));
        $this->assertTrue($usuario->getConnection()->getSchemaBuilder()->hasColumn('users', 'remember_token'));
        $this->assertTrue($usuario->getConnection()->getSchemaBuilder()->hasColumn('users', 'email_verified_at'));
        $this->assertTrue($usuario->getConnection()->getSchemaBuilder()->hasColumn('users', 'created_at'));
        $this->assertTrue($usuario->getConnection()->getSchemaBuilder()->hasColumn('users', 'updated_at'));
        
        // Verificar lista de columnas
        $columns = $usuario->getConnection()->getSchemaBuilder()->getColumnListing('users');
        $columnasEsperadas = [
            'id', 'name', 'email', 'password', 'rol',
            'remember_token', 'email_verified_at', 'created_at', 'updated_at'
        ];
        
        foreach ($columnasEsperadas as $columna) {
            $this->assertContains($columna, $columns, "La columna '$columna' debería existir en la tabla users");
        }
    }

    /**
     * PRUEBA 5: Verificar helpers de roles
     * 
     * Esta prueba verifica que los métodos esAdministrador() y esEmpleado()
     * funcionan correctamente.
     */
    public function test_helpers_de_roles_funcionan_correctamente()
    {
        // Crear usuario administrador
        $admin = new User([
            'name' => 'Admin User',
            'email' => 'admin@example.com',
            'password' => 'password',
            'rol' => User::ROL_ADMINISTRADOR
        ]);

        // Crear usuario empleado
        $empleado = new User([
            'name' => 'Employee User',
            'email' => 'employee@example.com',
            'password' => 'password',
            'rol' => User::ROL_EMPLEADO
        ]);

        // Verificar administrador
        $this->assertTrue($admin->esAdministrador());
        $this->assertFalse($admin->esEmpleado());
        
        // Verificar empleado
        $this->assertTrue($empleado->esEmpleado());
        $this->assertFalse($empleado->esAdministrador());
    }

    /**
     * PRUEBA 6: Verificar casts de atributos
     * 
     * Esta prueba verifica que los casts definidos en el modelo funcionan
     * correctamente (password hasheado, fechas convertidas a Carbon).
     */
    public function test_atributos_tienen_casts_correctos()
    {
        // Crear y guardar usuario en BD (así se aplican los casts correctamente)
        $usuario = User::create([
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => 'plain_text_password',
            'rol' => User::ROL_ADMINISTRADOR
        ]);

        // Actualizar email_verified_at y guardar
        $usuario->update(['email_verified_at' => '2026-03-19 10:00:00']);

        // Recuperar del BD para obtener casts aplicados
        $usuarioRecuperado = User::find($usuario->id);

        // Verificar que la contraseña se hashea automáticamente
        $this->assertNotEquals('plain_text_password', $usuarioRecuperado->password);
        $this->assertStringStartsWith('$2y$', $usuarioRecuperado->password); // Formato bcrypt
        
        // Verificar que email_verified_at es instancia de Carbon gracias al cast
        $this->assertInstanceOf(\Illuminate\Support\Carbon::class, $usuarioRecuperado->email_verified_at);
        $this->assertEquals('2026-03-19', $usuarioRecuperado->email_verified_at->format('Y-m-d'));
    }
}
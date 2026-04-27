# Plan de Desarrollo

## Resumen

Este documento detalla el plan de desarrollo estructurado para completar el Sistema de Reservas y Venta de Boletos para Cine. El proyecto sigue un enfoque estructurado para implementar componentes faltantes críticos manteniendo calidad de código y consistencia arquitectónica.

**Estado Actual:**
- Contexto Catálogo: ✅ Completo (100%)
- Contexto Compartido: ✅ Completo (100%)
- Contexto Reservas: ❌ Faltante (0%)
- Contexto Programación: ❌ Faltante (0%)
- Contexto Cine: ❌ Faltante (0%)

**Duración Estimada:** 6 semanas (2 desarrolladores)
**Fecha Inicio:** 27 de abril, 2026
**Fecha Término Objetivo:** 8 de junio, 2026

---

## Fase 1: Fundamentos y Base de Datos (Semanas 1-2)

### Objetivos
- Establecer esquema base datos completo
- Implementar agregados dominio core
- Crear infraestructura repositorios
- Configurar framework pruebas

### Sprint 1.1: Esquema Base Datos (Días 1-3)

#### Tareas
1. **Migración Reservas** (`create_bookings_table`)
   - Columnas: id, customer_id, status, total_amount, created_at, confirmed_at, expires_at
   - Claves foráneas: customer_id → users
   - Índices: status, created_at
   - Estimado: 4 horas

2. **Migración Tickets** (`create_tickets_table`)
   - Columnas: id, booking_id, showtime_id, seat_number, price
   - Claves foráneas: booking_id → bookings, showtime_id → showtimes
   - Índices: booking_id, showtime_id
   - Estimado: 4 horas

3. **Migración Funciones** (`create_showtimes_table`)
   - Columnas: id, movie_id, auditorium_id, start_time, end_time, status
   - Claves foráneas: movie_id → movies, auditorium_id → auditoriums
   - Índices: movie_id, auditorium_id, start_time
   - Estimado: 4 horas

4. **Migración Auditorios** (`create_auditoriums_table`)
   - Columnas: id, name, type, total_capacity, rows, seats_per_row
   - Índices: type
   - Estimado: 4 horas

5. **Migración Asientos** (`create_seats_table`)
   - Columnas: id, auditorium_id, row, number, type, price_multiplier
   - Claves foráneas: auditorium_id → auditoriums
   - Único: auditorium_id + row + number
   - Estimado: 4 horas

#### Criterios Aceptación
- Todas migraciones ejecutan exitosamente
- Restricciones base datos definidas correctamente
- Integridad referencial garantizada
- Índices creados para rendimiento

#### Entregables
- 5 archivos migración
- Diagrama esquema base datos
- Procedimientos rollback documentados

### Sprint 1.2: Agregados Dominio (Días 4-7)

#### Tareas
1. **Implementar Agregado Reserva** (Días 4-5)
   - Propiedades: id, customer, tickets, status, totalAmount, timestamps
   - Métodos: create(), reserveSeat(), confirm(), cancel(), expire(), calculateTotal()
   - Transiciones estado: PENDING → CONFIRMED/CANCELLED/EXPIRED
   - Eventos dominio: BookingCreated, BookingConfirmed, BookingCancelled, BookingExpired
   - Reglas validación
   - Estimado: 16 horas

2. **Implementar Agregado Función** (Días 6-7)
   - Propiedades: id, movieId, auditoriumId, startTime, endTime, seatAvailabilities
   - Métodos: schedule(), isSeatAvailable(), reserveSeat(), cancelReservation()
   - Transiciones estado: SCHEDULED → IN_PROGRESS → COMPLETED/CANCELLED
   - Detección conflictos
   - Eventos dominio: ShowtimeScheduled, ShowtimeCancelled
   - Estimado: 16 horas

#### Criterios Aceptación
- Todas reglas negocio implementadas
- Transiciones estado validadas
- Eventos dominio disparados correctamente
- Pruebas unitarias pasan
- Cobertura código ≥ 80%

#### Entregables
- Booking.php (implementación completa)
- Showtime.php (implementación completa)
- Value objects asociados
- Archivos pruebas unitarias

### Sprint 1.3: Infraestructura Repositorios (Días 8-10)

#### Tareas
1. **Implementación Repositorio Reservas** (Días 8-9)
   - EloquentBookingRepository
   - Métodos: save(), findById(), findByCustomer(), findByShowtime(), findAvailableSeats()
   - Optimización queries
   - Estimado: 12 horas

2. **Implementación Repositorio Funciones** (Días 9-10)
   - EloquentShowtimeRepository
   - Métodos: save(), findById(), findByMovie(), findByDateRange(), findAvailable()
   - Queries disponibilidad
   - Estimado: 12 horas

3. **Mappers Adicionales** (Día 10)
   - BookingMapper
   - TicketMapper
   - ShowtimeMapper (mejorar)
   - Estimado: 8 horas

#### Criterios Aceptación
- Todas interfaces repositorio implementadas completamente
- Mapeo datos correcto
- Rendimiento queries aceptable
- Pruebas integración pasan

#### Entregables
- EloquentBookingRepository.php
- EloquentShowtimeRepository.php
- Clases Mapper
- Pruebas integración

---

## Fase 2: Lógica Core y Reglas Negocio (Semanas 2-3)

### Objetivos
- Implementar flujo reservas
- Añadir mecanismo bloqueo asientos
- Integrar estrategias precios
- Manejar eventos dominio

### Sprint 2.1: Handlers Reservas (Días 11-14)

#### Tareas
1. **CreateBookingHandler** (Días 11-12)
   - Validar datos entrada
   - Crear agregado reserva
   - Reservar asientos iniciales
   - Calcular total
   - Persistir base datos
   - Disparar eventos
   - Estimado: 16 horas

2. **ReserveSeatHandler** (Días 12-13)
   - Verificar disponibilidad asiento
   - Adquirir bloqueo (Redis)
   - Añadir asiento a reserva
   - Actualizar precio
   - Manejar conflictos
   - Estimado: 16 horas

3. **ConfirmBookingHandler** (Días 13-14)
   - Validar estado reserva
   - Verificar todos asientos bloqueados
   - Procesar pago (gateway)
   - Confirmar reserva
   - Generar tickets
   - Enviar notificaciones
   - Estimado: 16 horas

4. **CancelBookingHandler** (Días 13-14)
   - Validar política cancelación
   - Liberar bloqueos asientos
   - Actualizar estado reserva
   - Procesar reembolso si aplica
   - Enviar notificaciones
   - Estimado: 12 horas

#### Criterios Aceptación
- Todos handlers orquestan lógica dominio correctamente
- Manejo errores exhaustivo
- Reglas negocio aplicadas
- Pruebas integración pasan
- Cobertura código ≥ 80%

#### Entregables
- Implementaciones handlers
- Capa servicios actualizada
- Pruebas integración

### Sprint 2.2: Sistema Bloqueo Asientos (Días 15-17)

#### Tareas
1. **Redis Seat Lock Manager** (Días 15-16)
   - Implementar adquisición bloqueo
   - Expiración automática (15 minutos)
   - Liberación manual
   - Operaciones atómicas
   - Renovación bloqueo
   - Estimado: 16 horas

2. **Control Concurrencia** (Días 16-17)
   - Transacciones base datos
   - Manejo timeout bloqueos
   - Prevención condiciones carrera
   - Detección deadlocks
   - Lógica reintento
   - Estimado: 16 horas

3. **Pruebas Integración** (Día 17)
   - Simulación reservas concurrentes
   - Pruebas estrés
   - Benchmarking rendimiento
   - Estimado: 8 horas

#### Criterios Aceptación
- No hay doble-reserva posible
- Bloqueos expiran automáticamente
- Rendimiento aceptable bajo carga
- 100+ usuarios concurrentes soportados

#### Entregables
- Implementación SeatLockManager
- Resultados pruebas carga
- Reporte rendimiento

### Sprint 2.3: Precios y Políticas (Días 18-21)

#### Tareas
1. **Integración Estrategias Precios** (Días 18-19)
   - StandardPrice implementación
   - PremiumPrice implementación
   - PromotionalPrice implementación
   - WeekendPrice (añadir si necesario)
   - Orquestación calculadora precios
   - Estimado: 16 horas

2. **Implementación Políticas** (Días 19-20)
   - Reglas ReservationPolicy
   - Reglas CancellationPolicy
   - Cálculo reembolso
   - Validación políticas
   - Estimado: 12 horas

3. **Integración Pago** (Días 20-21)
   - Interfaz gateway pago
   - Manejo transacciones
   - Seguimiento estado pago
   - Manejo errores
   - Modo pruebas
   - Estimado: 16 horas

#### Criterios Aceptación
- Precio correcto en todos escenarios
- Políticas aplicadas consistentemente
- Procesamiento pago funciona
- Datos transacción persistidos

#### Entregables
- Servicio precios
- Clases políticas
- Integración pago
- Gateway pago pruebas

---

## Fase 3: Capa API y Controladores (Semana 3)

### Objetivos
- Exponer funcionalidad reservas via API
- Implementar endpoints RESTful
- Añadir validación requests
- Documentar API

### Sprint 3.1: Controladores API (Días 22-24)

#### Tareas
1. **BookingController** (Días 22-23)
   - POST /api/bookings - Crear reserva
   - GET /api/bookings/{id} - Obtener detalles
   - POST /api/bookings/{id}/confirm - Confirmar
   - DELETE /api/bookings/{id} - Cancelar
   - GET /api/bookings - Listar reservas usuario
   - Validación requests
   - Formateo respuestas
   - Estimado: 24 horas

2. **ShowtimeController** (Día 24)
   - GET /api/showtimes - Listar funciones
   - GET /api/showtimes/{id} - Obtener detalles
   - GET /api/showtimes/{id}/seats - Disponibilidad asientos
   - Validación requests
   - Formateo respuestas
   - Estimado: 12 horas

3. **AuditoriumController** (Día 24)
   - GET /api/auditoriums - Listar auditorios
   - GET /api/auditoriums/{id} - Obtener detalles
   - Endpoints admin (CRUD) - si hay tiempo
   - Estimado: 12 horas

#### Criterios Aceptación
- Todos endpoints funcionales
- Códigos estado HTTP correctos
- Formato respuestas consistente
- Validación input funcionando
- Respuestas error útiles

#### Entregables
- Implementaciones controladores
- Documentación API
- Colección Postman

### Sprint 3.2: Rutas y Middleware (Días 25-26)

#### Tareas
1. **Rutas API** (Día 25)
   - Definir rutas routes/api.php
   - Agrupamiento rutas
   - Prefijo configuración
   - Versionado (v1/)
   - Estimado: 8 horas

2. **Middleware** (Día 25-26)
   - Middleware autenticación
   - Middleware autorización
   - Rate limiting
   - Request logging
   - Estimado: 12 horas

3. **Validación Requests** (Día 26)
   - Form requests cada endpoint
   - Reglas validación
   - Mensajes validación custom
   - Sanitización
   - Estimado: 12 horas

#### Criterios Aceptación
- Rutas configuradas correctamente
- Medidas seguridad implementadas
- Validación previene datos inválidos
- API sigue convenciones REST

#### Entregables
- Definición rutas
- Middleware implementations
- Clases Form Request

### Sprint 3.3: Pruebas API (Día 27)

#### Tareas
1. **Test Suite API** (Día 27)
   - Pruebas endpoints reserva
   - Pruebas endpoints funciones
   - Pruebas autenticación
   - Pruebas autorización
   - Pruebas respuestas error
   - Pruebas rendimiento
   - Estimado: 16 horas

#### Criterios Aceptación
- Todos endpoints testeados
- Casos borde cubiertos
- Rendimiento aceptable
- Cobertura código ≥ 80%

#### Entregables
- Suite pruebas API
- Reporte cobertura
- Benchmarks rendimiento

---

## Fase 4: Implementación Frontend (Semanas 4-5)

### Objetivos
- Construir interfaz usuario
- Implementar componentes Livewire
- Crear diseño responsive
- Añadir interactividad

### Sprint 4.1: Componentes Livewire (Días 28-32)

#### Tareas
1. **Componente BookingFlow** (Días 28-30)
   - Asistente reserva multi-paso
   - Selección función
   - Selección asientos
   - Detalles cliente
   - Integración pago
   - Página confirmación
   - Gestión estado
   - Estimado: 40 horas

2. **Componente SeatSelection** (Días 30-31)
   - Mapa asientos interactivo
   - Disponibilidad tiempo real
   - Diferenciación tipo asiento
   - Mostrar precio
   - Lógica selección
   - Estimado: 24 horas

3. **Componente ShowtimeCalendar** (Días 31-32)
   - Selección fecha
   - Listado funciones
   - Información película
   - Indicadores disponibilidad
   - Estimado: 16 horas

#### Criterios Aceptación
- Componentes totalmente funcionales
- Experiencia usuario fluida
- Actualizaciones tiempo real
- Diseño responsive

#### Entregables
- Clases componentes Livewire
- Plantillas Blade
- Activos JavaScript
- Documentación componentes

### Sprint 4.2: UI/UX Implementation (Días 33-37)

#### Tareas
1. **Plantillas Blade** (Días 33-34)
   - Plantillas layout
   - Vistas componentes
   - Páginas error
   - Plantillas email
   - Estimado: 24 horas

2. **Estilizado** (Días 34-35)
   - Configuración Tailwind CSS
   - Estilo componentes
   - Diseño responsive
   - Animaciones/transiciones
   - Estimado: 24 horas

3. **Mejoras JavaScript** (Días 36-37)
   - Integración Alpine.js
   - Actualizaciones tiempo real
   - Validación formularios
   - Elementos interactivos
   - Estimado: 24 horas

#### Criterios Aceptación
- Diseño consistente
- Apariencia profesional
- Navegación intuitiva
- Cargas página rápidas

#### Entregables
- Componentes estilizados
- Sistema diseño
- Documentación UI/UX

### Sprint 4.3: Dashboard Cliente (Días 38-40)

#### Tareas
1. **Gestión Reservas** (Días 38-39)
   - Ver reservas
   - Cancelar reserva
   - Descargar tickets
   - Historial reservas
   - Estimado: 24 horas

2. **Perfil Usuario** (Día 40)
   - Detalles cuenta
   - Preferencias reserva
   - Configuración notificaciones
   - Gestión contraseña
   - Estimado: 16 horas

#### Criterios Aceptación
- Usuarios pueden gestionar reservas
- Privacidad datos mantenida
- Funciones intuitivas

#### Entregables
- Componentes dashboard
- Interfaz usuario

---

## Fase 5: Pruebas y Calidad (Semanas 5-6)

### Objetivos
- Cobertura pruebas completa
- Corrección bugs
- Optimización rendimiento
- Revisión seguridad

### Sprint 5.1: Implementación Pruebas (Días 41-44)

#### Tareas
1. **Pruebas Unitarias** (Días 41-42)
   - Pruebas agregado reserva
   - Pruebas agregado función
   - Pruebas handlers
   - Pruebas value objects
   - Pruebas políticas
   - Estimado: 32 horas

2. **Pruebas Integración** (Días 42-43)
   - Pruebas repositorios
   - Pruebas servicios
   - Pruebas manejo eventos
   - Pruebas integración pago
   - Estimado: 24 horas

3. **Pruebas Funcionales** (Día 43-44)
   - Flujo reserva end-to-end
   - Pruebas interacción UI
   - Pruebas contrato API
   - Escenarios error
   - Estimado: 24 horas

#### Criterios Aceptación
- Cobertura código ≥ 80%
- Caminos críticos testeados
- Casos borde cubiertos
- Pruebas pasan consistentemente

#### Entregables
- Suite pruebas completa
- Reporte cobertura
- Documentación pruebas

### Sprint 5.2: Corrección Bugs y Optimización (Días 45-47)

#### Tareas
1. **Triaje Bugs** (Día 45)
   - Identificar issues críticos
   - Priorizar correcciones
   - Implementar soluciones
   - Estimado: 16 horas

2. **Optimización Rendimiento** (Días 46-47)
   - Optimización queries
   - Implementación caching
   - Optimización activos
   - Pruebas carga
   - Estimado: 24 horas

#### Criterios Aceptación
- Sin bugs críticos
- Objetivos rendimiento cumplidos
- Calidad código alta

#### Entregables
- Lista corrección bugs
- Reporte rendimiento
- Resumen optimización

### Sprint 5.3: Seguridad y Despliegue (Días 48-50)

#### Tareas
1. **Revisión Seguridad** (Días 48-49)
   - Escaneo vulnerabilidades
   - Revisión código
   - Pruebas penetración
   - Correcciones seguridad
   - Estimado: 24 horas

2. **Pipeline Despliegue** (Día 50)
   - Configuración CI/CD
   - Configuración entornos
   - Scripts despliegue
   - Procedimientos rollback
   - Estimado: 16 horas

#### Criterios Aceptación
- Vulnerabilidades críticas abordadas
- Despliegue automatizado
- Rollback testeado

#### Entregables
- Reporte seguridad
- Pipeline CI/CD
- Documentación despliegue

---

## Fase 6: Polish Final y Entrega (Semana 6)

### Objetivos
- Completar documentación
- Materiales entrenamiento
- Pruebas finales
- Entrega proyecto

### Sprint 6.1: Documentación (Días 51-52)

#### Tareas
1. **Documentación Técnica** (Día 51)
   - Documentación arquitectura
   - Documentación API
   - Documentación código
   - Guía setup
   - Estimado: 16 horas

2. **Documentación Usuario** (Día 52)
   - Manual usuario
   - Guía admin
   - Troubleshooting
   - FAQ
   - Estimado: 16 horas

#### Criterios Aceptación
- Documentación completa
- Clara y precisa
- Fácil de seguir

#### Entregables
- Documentación técnica
- Manual usuario
- Referencia API

### Sprint 6.2: Entrenamiento y UAT (Días 53-54)

#### Tareas
1. **Materiales Entrenamiento** (Día 53)
   - Entrenamiento admin
   - Entrenamiento usuario
   - Onboarding desarrollador
   - Estimado: 16 horas

2. **Pruebas Finales** (Día 54)
   - Coordinación UAT
   - Retroalimentación
   - Correcciones finales
   - Estimado: 16 horas

#### Criterios Aceptación
- Stakeholders entrenados
- UAT aprobado
- Listo para producción

#### Entregables
- Sesiones entrenamiento
- Reporte retroalimentación
- Checklist producción

### Sprint 6.3: Cierre Proyecto (Días 55-56)

#### Tareas
1. **Revisión Proyecto** (Día 55)
   - Lecciones aprendidas
   - Métricas éxito
   - Mejoras futuras
   - Estimado: 8 horas

2. **Entrega** (Día 56)
   - Entrega documentación
   - Entrega código
   - Transferencia accesos
   - Estimado: 8 horas

#### Criterios Aceptación
- Proyecto completo
- Todos entregables satisfechos
- Cliente satisfecho

#### Entregables
- Reporte final
- Archivo proyecto
- Paquete entrega

---

## Requerimientos Recursos

### Composición Equipo

**Fase 1-2 (Enfoque Backend):**
- 2 Desarrolladores Senior PHP/Laravel
- 1 Ingeniero QA (medio tiempo)

**Fase 3-4 (Full Stack):**
- 2 Desarrolladores Senior PHP/Laravel
- 1 Desarrollador Frontend (Livewire)
- 1 Ingeniero QA (tiempo completo)

**Fase 5-6 (Pruebas y Despliegue):**
- 1 Desarrollador Senior PHP/Laravel
- 1 Ingeniero QA (tiempo completo)
- 1 Ingeniero DevOps (medio tiempo)

### Herramientas y Tecnologías

- Desarrollo: PHP 8.2, Laravel 12, Livewire 3
- Base Datos: MySQL/SQLite
- Cache: Redis
- Colas: Redis/Database
- Pruebas: Pest/PHPUnit
- Frontend: Tailwind CSS, Alpine.js
- CI/CD: GitHub Actions
- Despliegue: Laravel Forge/Envoyer

### Infraestructura

- Entorno desarrollo
- Entorno staging
- Entorno producción
- Repositorio Git
- Seguimiento issues
- Wiki documentación

---

## Hitos

| Hito | Fecha Objetivo | Entregables |
|------|----------------|-------------|
| Fase 1 Completa | 11 mayo, 2026 | Base datos, Agregados dominio, Repositorios |
| Fase 2 Completa | 18 mayo, 2026 | Lógica reservas, Bloqueo asientos, Precios |
| Fase 3 Completa | 25 mayo, 2026 | Capa API, Controladores, Rutas |
| Fase 4 Completa | 1 junio, 2026 | Frontend, Livewire, UI |
| Fase 5 Completa | 8 junio, 2026 | Pruebas, Bugs, Rendimiento |
| Fase 6 Completa | 15 junio, 2026 | Documentación, Entrenamiento, Entrega |
| **Proyecto Completo** | **15 junio, 2026** | **Sistema Producción** |

---

## Evaluación Riesgos

### Riesgos Técnicos

| Riesgo | Probabilidad | Impacto | Mitigación |
|--------|------------|---------|------------|
| Problemas concurrencia | Alta | Crítico | Implementación temprana, pruebas exhaustivas |
| Integración pago | Media | Alto | Gateway establecido, modo pruebas |
| Problemas rendimiento | Media | Alto | Pruebas tempranas, optimización |
| Scope creep | Media | Medio | Requerimientos claros, control cambios |

### Riesgos Proyecto

| Riesgo | Probabilidad | Impacto | Mitigación |
|--------|------------|---------|------------|
| Disponibilidad recursos | Baja | Alto | Cross-training, documentación |
| Desviación timeline | Media | Medio | Buffer tiempo, revisiones regulares |
| Deuda técnica | Baja | Medio | Code reviews, refactoring |
| Problemas integración | Media | Alto | Integración temprana, pruebas |

---

## Criterios Éxito

### Requerimientos Funcionales
- [ ] Todas las reservas se procesan correctamente
- [ ] No hay doble-reserva posible
- [ ] Precios correctos en todos los escenarios
- [ ] Interfaz de usuario intuitiva
- [ ] Endpoints API funcionales
- [ ] Notificaciones email se envían
- [ ] Reportes se generan correctamente

### Requerimientos No Funcionales
- [ ] Page load < 2 segundos
- [ ] API response < 500ms
- [ ] Soporta 100+ usuarios concurrentes
- [ ] 99.9% uptime
- [ ] 80%+ cobertura pruebas
- [ ] Cero bugs críticos

### Requerimientos Calidad
- [ ] Código sigue estándares PSR
- [ ] Documentación completa
- [ ] Auditoría seguridad aprobada
- [ ] Rendimiento optimizado
- [ ] Arquitectura escalable

---

## Estimación Presupuesto

### Costos Desarrollo (6 semanas, 2 desarrolladores)
- Salarios desarrolladores: $24,000
- QA/testing: $6,000
- DevOps/despliegue: $3,000
- **Total Personal:** $33,000

### Infraestructura
- Entornos desarrollo: $500
- Staging/producción: $1,500
- Servicios terceros: $1,000
- **Total Infraestructura:** $3,000

### Misceláneos
- Herramientas/licencias: $1,000
- Entrenamiento: $1,000
- Contingencia (10%): $3,700
- **Total Misceláneos:** $5,700

**Total Presupuesto: $41,700**

---

## Suposiciones

1. El código existente Catálogo permanece estable
2. No se requieren cambios arquitectónicos mayores
3. Servicios terceros disponibles (pago, email)
4. Stakeholders disponibles para revisiones
5. No hay cambios de alcance durante desarrollo
6. Recursos desarrollo disponibles adecuadamente
7. Conocimiento Laravel existente dentro equipo

---

## Dependencias

### Externas
- Disponibilidad gateway pago
- Proveedor servicio email
- Entorno hospedaje
- Registro dominio (si aplica)

### Internas
- Retroalimentación stakeholders oportuna
- Entorno testing listo
- Entorno producción configurado
- Disponibilidad equipo

---

## Fuera de Alcance

- Desarrollo app móvil
- Analíticas avanzadas/dashboard
- Multi-idioma soporte
- Multi-moneda soporte
- Programa lealtad
- Características marketing
- Integración POS
- Reportes avanzados

*Estos pueden considerarse para fases futuras*

---

## Conclusión

Este plan de desarrollo proporciona un enfoque claro y estructurado para completar el Sistema de Reservas y Venta de Boletos para Cine. El enfoque por fases permite entregar funcionalidad core temprana mientras mantiene calidad y gestiona riesgos.

**Factores Clave Éxito:**
1. Seguir patrones existentes en Catálogo
2. Priorizar contexto Reservas (mayor valor negocio)
3. Pruebas exhaustivas, especialmente concurrencia
4. Comunicación regular con stakeholders
5. Adherencia a timeline y presupuesto

**Siguientes Pasos:**
1. Revisar y aprobar plan
2. Configurar entorno desarrollo
3. Comenzar Fase 1 implementación
4. Programar check-ins regulares

---

*Versión Plan: 1.0*  
*Creado: 2026-04-27*  
*Próxima Revisión: 2026-05-04*  
*Duración Estimada: 6 semanas*  
*Presupuesto Estimado: $41,700*

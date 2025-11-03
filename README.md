# Lab 2 - Aplicación PHP en AWS ECS con CI/CD

Aplicación web estilo Twitter desplegada en AWS ECS con pipeline CI/CD automatizado, alta disponibilidad y Service Discovery.

## 🏗️ Arquitectura

### Componentes principales:
- **Frontend**: Aplicación PHP 8.0 con Apache
- **Backend**: MySQL 8.0 con almacenamiento persistente (EFS)
- **Infraestructura**: AWS ECS (EC2 launch type)
- **CI/CD**: CodePipeline + CodeBuild
- **Networking**: ALB + Route53 + Service Discovery
- **Seguridad**: Security Groups + IAM Roles + Parameter Store

### Diagrama de arquitectura:
```
┌─────────────┐
│   Route53   │ (lab2.appesce.ownboarding.teratest.net)
└──────┬──────┘
       │
       ▼
┌─────────────┐
│     ALB     │ (HTTPS - Puerto 443)
└──────┬──────┘
       │
       ▼
┌──────────────────────────────────────┐
│          ECS Cluster (lab2-ecs)      │
│  ┌────────────────┐  ┌──────────────┐│
│  │   Frontend     │  │   Database   ││
│  │   (2 tasks)    │  │   (1 task)   ││
│  │ lab2-frontend- │  │   MySQL 8.0  ││
│  │   container    │  │   + EFS      ││
│  └────────┬───────┘  └──────┬──────┬┘│
│           │                 │      │ │
│           └──Service Discovery─────┘ │
│      lab2-tf-database-service.       │
│         database-name-space          │
└──────────────────────────────────────┘
           │
           ▼
    ┌──────────────┐
    │ Parameter    │
    │   Store      │
    └──────────────┘
```

## 🚀 Características

✅ **Alta disponibilidad**: 2 instancias del frontend en diferentes AZs  
✅ **CI/CD automatizado**: Deploy automático con cada push a `main`  
✅ **Service Discovery**: Comunicación interna mediante DNS privado  
✅ **Almacenamiento persistente**: Base de datos con EFS  
✅ **HTTPS**: Certificado SSL/TLS configurado  
✅ **Gestión de secretos**: Credenciales en Parameter Store  

## 📦 Stack tecnológico

| Componente | Tecnología | Versión |
|------------|------------|---------|
| Frontend | PHP + Apache | 8.0 |
| Base de datos | MySQL | 8.0 |
| Orquestación | AWS ECS | - |
| CI/CD | CodePipeline + CodeBuild | - |
| Source Control | GitHub | - |
| Container Registry | Amazon ECR | - |
| Load Balancer | Application Load Balancer | - |
| DNS | Route 53 | - |
| Secrets | AWS Systems Manager Parameter Store | - |

## 🔧 Variables de entorno

El frontend utiliza las siguientes variables (definidas en Parameter Store):

- `DATABASE_HOST`: Hostname del servicio MySQL via Service Discovery
- `DATABASE_NAME`: Nombre de la base de datos
- `DATABASE_USER`: Usuario de la base de datos
- `DATABASE_PASSWORD`: Contraseña (encriptada)

## 🏃 Pipeline CI/CD

### Flujo automático:
1. **Source**: Detecta cambios en GitHub (branch `main`)
2. **Build**: CodeBuild construye imagen Docker y la sube a ECR
3. **Deploy**: ECS actualiza el servicio con la nueva imagen

### Tiempo promedio de deployment: ~10 minutos

## 📊 Recursos AWS utilizados

### Compute:
- ECS Cluster: `lab2-ecs`
- Auto Scaling Group: 4 instancias EC2 t2.micro
- Task Definitions: `lab2-frontend-tf:X`, `lab2-tf-database:X`

### Networking:
- VPC: `lab2-vpc`
- Subnets: 2 privadas, 2 públicas
- ALB: `lab2-alb`
- Target Group: `lab2-tg`
- Security Groups: frontend, database, ALB

### Storage:
- EFS: Para persistencia de MySQL
- ECR: `frontend-lab2`

### CI/CD:
- CodePipeline: `codepipeline-lab2`
- CodeBuild: `codebuild-lab2`

## 🌐 URLs

- **Aplicación**: https://lab2.appesce.ownboarding.teratest.net
- **ALB**: http://lab2-alb-346733634.us-east-1.elb.amazonaws.com

## 👥 Usuarios de prueba

La aplicación incluye 4 usuarios precargados:
- Princess Leia (@Princess_Leia)
- Luke Skywalker (@Luke)
- Obi-Wan Kenobi (@Obi-Wan)
- Anakin Skywalker (@Darth_Vader)

## 🔐 Seguridad

- Credenciales almacenadas en Parameter Store (encriptadas)
- Security Groups con reglas restrictivas
- IAM Roles con permisos mínimos necesarios
- HTTPS habilitado con certificado válido
- Base de datos en subnet privada (sin acceso público)

## 📈 Escalabilidad

### Actual:
- Frontend: 2 tasks (puede escalar a más)
- Database: 1 task (master único)
- EC2: 4 instancias (puede escalar automáticamente)

### Mejoras futuras:
- Implementar Auto Scaling basado en CPU/memoria
- Migrar a Fargate para reducir gestión de infraestructura
- Implementar réplicas de lectura para MySQL

## 🛠️ Desarrollo local
```bash
# Clonar repositorio
git clone <repo-url>
cd lab2

# Construir imagen
docker build -t lab2-frontend .

# Ejecutar con docker-compose
docker-compose up -d
```

## 📝 Lecciones aprendidas

### Desafíos principales:
1. **Autoloading de clases PHP**: Requerió configurar `auto_prepend_file` en Apache
2. **Service Discovery**: Configuración de DNS privado para comunicación interna
3. **Variables de entorno**: Integración correcta con Parameter Store y ECS
4. **Memoria insuficiente**: Escalado de instancias EC2 para soportar deployments

### Trade-offs técnicos:
- **EC2 vs Fargate**: Elegimos EC2 por costo y control granular
- **EFS vs RDS**: EFS permite MySQL containerizado con persistencia
- **Monorepo**: Todo el código en un repositorio simplifica CI/CD

## 👨‍💻 Autor

**Agustín Pesce**  
Laboratorio 2 - Cloud/DevOps/Infraestructura

---

**Fecha**: Noviembre 2025  
**Versión**: 1.0 (Producción)
```

---

## 📊 2. Diagrama de arquitectura detallado

Te recomiendo crear un diagrama visual. Puedes usar:

### **Opción A: draw.io (recomendado)**
1. Ve a https://app.diagrams.net/
2. Crea un diagrama con los componentes
3. Exporta como PNG/PDF

### **Opción B: AWS Architecture Icons**
1. Descarga iconos oficiales: https://aws.amazon.com/architecture/icons/
2. Usa PowerPoint o similar
3. Crea el diagrama

### **Componentes a incluir:**
```
Internet
   ↓
Route 53 (lab2.appesce.ownboarding.teratest.net)
   ↓
Application Load Balancer (lab2-alb)
   ↓
┌─────────────────────────────────────────┐
│         ECS Cluster (lab2-ecs)          │
│                                         │
│  ┌──────────────┐    ┌──────────────┐  │
│  │  Frontend    │    │  Database    │  │
│  │  Service     │───▶│  Service     │  │
│  │  (2 tasks)   │    │  (1 task)    │  │
│  └──────────────┘    └──────────────┘  │
│         ↓                    ↓          │
│    Target Group          EFS Volume    │
└─────────────────────────────────────────┘
         ↓                    ↓
   Parameter Store      CloudWatch Logs

GitHub → CodePipeline → CodeBuild → ECR → ECS
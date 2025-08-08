# View Structure Organization - ISDU Ticketing System

This document outlines the new organized view structure that separates views by user roles.

## 📁 New Folder Structure

```
resources/views/
├── admin/                    # Admin-only views
│   ├── users/ (moved out)    # Now in superadmin/
│   └── password/             # Password management (admin-specific)
│
├── superadmin/              # SuperAdmin-only views
│   ├── users/               # User management (CRUD)
│   │   ├── index.blade.php
│   │   ├── create.blade.php
│   │   ├── edit.blade.php
│   │   ├── review.blade.php
│   │   └── delete.blade.php
│   ├── tickets/             # Ticket creation (superadmin only)
│   │   └── create.blade.php
│   ├── references/          # Reference management
│   │   ├── index.blade.php
│   │   ├── create.blade.php
│   │   └── edit.blade.php
│   ├── reqOffice/          # Requesting Office management
│   │   ├── index.blade.php
│   │   ├── create.blade.php
│   │   ├── edit.blade.php
│   │   └── show.blade.php
│   ├── position/           # Position management
│   │   ├── index.blade.php
│   │   ├── create.blade.php
│   │   └── edit.blade.php
│   ├── status/             # Status management
│   │   ├── index.blade.php
│   │   ├── create.blade.php
│   │   └── edit.blade.php
│   └── unitResponsible/    # Unit Responsible management
│       └── index.blade.php
│
├── shared/                  # Views accessible by both Admin & SuperAdmin
│   ├── dashboard.blade.php  # Main dashboard
│   ├── profile.blade.php    # User profile
│   └── tickets/             # Ticket viewing/editing (shared)
│       ├── index.blade.php  # List all tickets
│       ├── show.blade.php   # View ticket details
│       ├── edit.blade.php   # Edit ticket (admin: status only)
│       └── my-tickets.blade.php # User's assigned tickets
│
├── auth/                    # Authentication views (unchanged)
├── layouts/                 # Layout templates (unchanged)
└── welcome.blade.php        # Welcome page (unchanged)
```

## 🔐 Role-Based Access Control

### SuperAdmin Only
- **User Management**: Create, edit, delete admin users
- **System Configuration**: Manage references, offices, positions, statuses
- **Ticket Creation**: Create new tickets
- **Full System Access**: All administrative functions

### Admin + SuperAdmin (Shared)
- **Ticket Management**: View and edit tickets (admin can only edit status)
- **Dashboard**: View system statistics and charts
- **Profile**: Manage own profile
- **My Tickets**: View assigned tickets

### Admin Specific
- **Password Management**: Change own password
- **Limited Ticket Access**: Can only edit ticket status, not create/delete

## 🚀 Controller Updates

### Updated Controller View Paths:

1. **AdminUserController** → `superadmin.users.*`
2. **TicketController**:
   - `create` → `superadmin.tickets.create`
   - `index`, `show`, `edit`, `my-tickets` → `shared.tickets.*`
3. **DashboardController** → `shared.dashboard`
4. **StatusController** → `superadmin.status.*`
5. **ReferenceController** → `superadmin.references.*`
6. **ReqOfficeController** → `superadmin.reqOffice.*`
7. **PositionController** → `superadmin.position.*`
8. **UserController** (profile) → `shared.profile`

## 📝 Benefits of This Organization

1. **Clear Separation**: Easy to identify which views belong to which role
2. **Security**: Prevents accidental access to admin-only functions
3. **Maintainability**: Easier to maintain and update role-specific features
4. **Scalability**: Easy to add new roles or features in the future
5. **Code Organization**: Better structure for team development

## 🛠️ Route Configuration

Routes are already properly configured with middleware:
- `role:super_admin` → SuperAdmin only routes
- `role:admin,super_admin` → Shared routes
- Individual role middleware as needed

## 📋 Migration Complete

All view files have been moved and controller references updated. The system now follows a clean, role-based view organization that enhances security and maintainability.

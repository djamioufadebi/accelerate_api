<template>
    <b-row>
      <b-col sm="12">
        <b-card no-body class="card">
          <div class="card-header d-flex justify-content-between flex-wrap">
            <div class="header-title">
              <h4 class="card-title mb-0">Liste des clients</h4>
            </div>
            <div class="d-flex align-items-center gap-3">
              <a href="#" class="text-center btn btn-primary d-flex gap-2" data-bs-toggle="modal" data-bs-target="#new-permission">
                <svg width="20" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                </svg>
                Nouveau client
              </a>

              <!-- <a href="#" class="text-center btn btn-primary d-flex gap-2" data-bs-toggle="modal" data-bs-target="#new-role">
                <svg width="20" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                </svg>
                New Role
              </a> -->
            </div>
          </div>


          <div class="card-body px-0">
            <div class="table-responsive">
              <table id="user-list-table" class="table table-striped hover" role="grid" data-toggle="data-table">
                <thead>
                  <tr class="ligth">
                    <th>Profile</th>
                    <th>Nom & présoms</th>
                    <th>Contact</th>
                    <th>Email</th>
                    <th>Pays</th>
                    <th>Status</th>
                    <th>Societe</th>
                    <th>Date d'adhésion</th>
                    <th style="min-width: 100px">Action</th>
                  </tr>
                </thead>
                <tbody>
                  <table-widget :list="tableData" />
                </tbody>
              </table>
            </div>
          </div>
          <!-- tableau liste clients -->
        </b-card>
      </b-col>
    </b-row>
    <!-- New Permission modal -->
    <div class="modal fade" id="new-permission" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropPermissionLabel" aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="staticBackdropPermissionLabel">Add Permission</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            <div class="form-group">
              <label class="form-label">Permission title</label>
              <input type="text" class="form-control" placeholder="Permission Title" />
            </div>
            <div class="text-start">
              <button type="button" class="btn btn-primary me-2" data-bs-dismiss="modal">Save</button>
              <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Cancel</button>
            </div>
          </div>
        </div>
      </div>
    </div>
    <!-- New Role modal -->
    <div class="modal fade" id="new-role" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropRoleLabel" aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="staticBackdropRoleLabel">Add Role</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            <div class="form-group">
              <label class="form-label">Role title</label>
              <input type="text" class="form-control" placeholder="Role Title" />
            </div>
            <div>
              <span>Status</span>
              <div class="form-check">
                <input class="form-check-input" type="radio" name="status" id="status-yes" value="option2" />
                <label class="form-check-label" for="status-yes"> Yes </label>
              </div>
              <div class="form-check">
                <input class="form-check-input" type="radio" name="status" id="status-no" value="option2" />
                <label class="form-check-label" for="status-no"> No </label>
              </div>
            </div>
            <div class="text-start mt-2">
              <button type="button" class="btn btn-primary me-2" data-bs-dismiss="modal">Save</button>
              <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Cancel</button>
            </div>
          </div>
        </div>
      </div>
    </div>
  </template>
  <script>
  import TableWidget from '@/components/widgets/users/TableWidget.vue'
  export default {
    components: {
      TableWidget
    },
    name: 'Clients-list',
    setup() {
      const tableData = [
        {
          image: require('@/assets/images/shapes/06.png'),
          name: 'Anna Sthesia',
          contact: '(760) 756 7568',
          email: 'annasthesia@gmail.com',
          country: 'USA',
          status: 'Active',
          company: 'Acme Corporation',
          date: '2019/12/01',
          color: 'bg-primary'
        },
        {
          image: require('@/assets/images/shapes/02.png'),
          name: 'Brock Lee',
          contact: '+62 5689 458 658',
          email: 'brocklee@gmail.com',
          country: 'Indonesia',
          status: 'Active',
          company: 'Soylent Corp',
          date: '2019/12/01',
          color: 'bg-primary'
        },
        {
          image: require('@/assets/images/shapes/03.png'),
          name: 'Dan Druff',
          contact: '+55 6523 456 856',
          email: 'dandruff@gmail.com',
          country: 'Brazil',
          status: 'Pending',
          company: 'Umbrella Corporation',
          date: '2019/12/01',
          color: 'bg-warning'
        },
        {
          image: require('@/assets/images/shapes/04.png'),
          name: 'Hans Olo',
          contact: '+91 2586 253 125',
          email: 'hansolo@gmail.com',
          country: 'India',
          status: 'Inactive',
          company: 'Vehement Capital',
          date: '2019/12/01',
          color: 'bg-danger'
        },
        {
          image: require('@/assets/images/shapes/05.png'),
          name: 'Lynn Guini',
          contact: '+27 2563 456 589',
          email: 'lynnguini@gmail.com',
          country: 'Africa',
          status: 'Active',
          company: 'Massive Dynamic',
          date: '2019/12/01',
          color: 'bg-primary'
        },
        {
          image: require('@/assets/images/shapes/06.png'),
          name: 'Eric Shun',
          contact: '+55 25685 256 589',
          email: 'ericshun@gmail.com',
          country: 'Brazil',
          status: 'Pending',
          company: 'Globex Corporation',
          date: '2019/12/01',
          color: 'bg-warning'
        },
        {
          image: require('@/assets/images/shapes/03.png'),
          name: 'aaronottix',
          contact: '(760) 756 7568',
          email: 'budwiser@ymail.com',
          country: 'USA',
          status: 'Hold',
          company: 'Acme Corporation',
          date: '2019/12/01',
          color: 'bg-info'
        },
        {
          image: require('@/assets/images/shapes/05.png'),
          name: 'Marge Arita',
          contact: '+27 5625 456 589',
          email: 'margearita@gmail.com',
          country: 'Africa',
          status: 'Complite',
          company: 'Vehement Capital',
          date: '2019/12/01',
          color: 'bg-success'
        },
        {
          image: require('@/assets/images/shapes/02.png'),
          name: 'Bill Dabear',
          contact: '+55 2563 456 589',
          email: 'billdabear@gmail.com',
          country: 'Brazil',
          status: 'Active',
          company: 'Massive Dynamic',
          date: '2019/12/01',
          color: 'bg-primary'
        }
      ]
      return {
        tableData
      }
    },
    data() {
      return {
        roles: [
          {
            title: 'Admin',
            status: 'true'
          },
          {
            title: 'Demo Admin',
            status: 'false'
          },
          {
            title: 'User',
            status: 'true'
          }
        ],
        rolename: 'Demo User',
        roleeditname: '',
        roleid: '',
        permissions: [
          {
            title: 'Role',
            status: 'true'
          },
          {
            title: 'Role Add',
            status: 'false'
          },
          {
            title: 'Role List',
            status: 'true'
          },
          {
            title: 'Permission',
            status: 'false'
          },
          {
            title: 'Permission Add',
            status: 'false'
          },
          {
            title: 'Permission List',
            status: 'true'
          }
        ],
        permissionsname: 'Demo Permission',
        permissionseditname: '',
        permissionsid: ''
      }
   
    },
    methods: {
      addrole() {
        const roledata = {
          title: this.rolename
        }
        this.roles.push(roledata)
      },
      deleteRole(roleid) {
        this.roles.splice(roleid, 1)
      },
      editrole(title, roleid) {
        this.roleeditname = title
        this.roleid = roleid
      },
      updaterole() {
        this.roles[this.roleid].title = this.roleeditname
      },
      addpermission() {
        const permissiondata = {
          title: this.permissionsname
        }
        this.permissions.push(permissiondata)
      },
      deletepermission(permissionsid) {
        this.permissions.splice(permissionsid, 1)
      },
      editpermission(title, permissionsid) {
        this.permissionseditname = title
        this.permissionsid = permissionsid
      },
      updatepermission() {
        this.permissions[this.permissionsid].title = this.permissionseditname
      }
    }
  }
  </script>
  
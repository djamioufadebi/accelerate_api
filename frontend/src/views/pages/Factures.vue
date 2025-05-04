<template>
    <b-row>
      <b-col sm="12">
        <b-card no-body class="card">
          <div class="card-header d-flex justify-content-between flex-wrap">
            <div class="header-title">
              <h4 class="card-title mb-0"> {{ isListe? listTitle: formTitle }} </h4>
            </div>
            <div class="d-flex align-items-center gap-3">
              <a v-if="isListe" href="#" class="text-center btn btn-primary d-flex gap-2" @click="isListe=false">
                <svg width="20" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                </svg>
                Nouvelle Facture
              </a>

              <a v-if="!isListe" href="#" class="text-center btn btn-primary d-flex gap-2" @click="isListe=true">
                <i class="fa-solid fa-list"> Liste des factures </i>
              </a>
              

              <!-- <a href="#" class="text-center btn btn-primary d-flex gap-2" data-bs-toggle="modal" data-bs-target="#new-role">
                <svg width="20" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                </svg>
                New Role
              </a> -->
            </div>
          </div>


          <div class="card-body px-0" v-if="isListe">
            <div class="table-responsive">
              <table id="user-list-table" class="table table-striped hover" role="grid" data-toggle="data-table">
                <thead>
                  <tr class="ligth">
                    <th>Image</th> 
                    <th>Référence</th>
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
          <!-- tableau liste facture -->

          <div class="card">
            <div class="card-body">
              <form :class="`row g-3 needs-validation ${valid ? 'was-validated' : ''}`" novalidate="" @submit.prevent="formSubmit">
                <div class="col-md-6 position-relative">
                  <label for="validationTooltip01" class="form-label">Désignation</label>
                  <input type="text" class="form-control" id="validationTooltip01" placeholder="Désignation" required="" />
                  <div class="valid-tooltip">Looks good!</div>
                </div>
                <div class="col-md-6 position-relative">
                  <label for="validationTooltip02" class="form-label">Last name</label>
                  <input type="text" class="form-control" id="validationTooltip02" value="Otto" required="" />
                  <div class="valid-tooltip">Looks good!</div>
                </div>
                <div class="col-md-6 position-relative">
                  <label for="validationTooltipUsername" class="form-label">Username</label>
                  <div class="input-group has-validation">
                    <span class="input-group-text" id="validationTooltipUsernamePrepend">@</span>
                    <input type="text" class="form-control" id="validationTooltipUsername" aria-describedby="validationTooltipUsernamePrepend" required="" />
                    <div class="invalid-tooltip">Please choose a unique and valid username.</div>
                  </div>
                </div>
                <div class="col-md-6 position-relative">
                  <label for="validationTooltip03" class="form-label">City</label>
                  <input type="text" class="form-control" id="validationTooltip03" required="" />
                  <div class="invalid-tooltip">Please provide a valid city.</div>
                </div>
                <div class="col-md-6 position-relative">
                  <label for="validationTooltip04" class="form-label">State</label>
                  <select class="form-select" id="validationTooltip04" required="">
                    <option selected="" disabled="" value="">Choose...</option>
                    <option>...</option>
                  </select>
                  <div class="invalid-tooltip">Please select a valid state.</div>
                </div>
                <div class="col-md-6 position-relative">
                  <label for="validationTooltip05" class="form-label">Zip</label>
                  <input type="text" class="form-control" id="validationTooltip05" required="" />
                  <div class="invalid-tooltip">Please provide a valid zip.</div>
                </div>
                <div class="col-12">
                  <button class="btn btn-danger" type="reset" @click="resetForm">Reset</button>
                  <button class="btn btn-primary ms-3" type="submit">Submit form</button>
                </div>
              </form>
            </div>
          </div>
          <!-- formulaire facture -->

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
  import { required } from '@vuelidate/validators'
  import { useVuelidate } from '@vuelidate/core'
  export default {
    components: {
      TableWidget
    },
    name: 'Clients-list',
    setup() {
      const v = useVuelidate()
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
        tableData,
        v
      }
    },
    data() {
      return {
        isListe:true,
        listTitle:'Liste des factures',
        formTitle:'Ajouter une facture',

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
        permissionsid: '',

        valid: false,
        form: {
          firstName: '',
          lastName: '',
          username: '',
          city: '',
          state: '',
          zip: '',
          agree: false
        }
      }
   
    },

    methods: {
      async formSubmit() {
        this.valid = true
        const result = await this.v.$validate()
        if (result) {
          // this.valid = true
        }
      },
      resetForm() {
        this.v.$reset()
        this.valid = false
      },

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
    },
    validations: {
     return:{
      form: {
        firstName: {
          required
        },
        lastName: {
          required
        },
        username: {
          required
        },
        city: {
          required
        },
        state: {
          required
        },
        zip: {
          required
        },
        agree: {
          required
        }
      }
     } 
    }
    
  }
  </script>
  
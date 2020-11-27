<template>
    <div class="jumbotron" >
            <h2>Edit User</h2>
            <div class="form-group">
                <label for="inputName">Name</label>
                <input
                    type="text" class="form-control" 
                    name="name" id="inputName"
                    placeholder="Fullname" value="" />
            </div>
            <div class="form-group">
                <label for="inputEmail">Email</label>
                <input
                    type="email" class="form-control" 
                    name="email" id="inputEmail"
                    placeholder="Email address" value=""/>
            </div>
            <div class="form-group">
                <label for="inputAge">Age</label>
                <input
                    type="number" class="form-control" 
                    name="age" id="inputAge"
                    placeholder="Age" value=""/>
            </div>
            

            <div class="form-group">
                <a class="btn btn-default" v-on:click.prevent="saveUser()">Save</a>
                <a class="btn btn-default" v-on:click.prevent="cancelEdit()">Cancel</a>
            </div>

        </div>
</template>
// <script>
// export default {
//     props:[
//         "user",
//         ],
//      methods:{
//         saveUser: function () {
//             axios.put('api/users/' + this.user.id, this.user)
//                 .then(response=>{
//                     console.log("estou aqui")
//                     this.$emit('user-saved', this.user)
//                     })
//                 .catch(error=>{
//                     console.log(error.errors)
//                     console.dir(error)
//                     console.dir(error.response.data.errors.age[0])

//                   this.$emit('user-saved-error',error.response.data.errors.age[0])
//                 })
                            
        
//         },

//         cancelEdit: function () {
//            axios.get('api/users/'+this.user.id)
//            .then(response=>{
//               this.$emit('canceled-edit', this.user)
//               })
              
//         },
//     }
    
// }
// </script>

<template>
    <div>
        <h1>ola</h1>
    <!-- <router-link to="/users">Users</router-link> - -->
   
    <router-view></router-view>
        
    </div>
</template>
<script>
import UserComponent from './components/user'
export default {
    components:{
         'user':UserComponent
         
    }
}
</script>
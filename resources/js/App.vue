<template>
    <div>
        
    <!-- <router-link to="/users">Users</router-link> - -->
   
    <router-view></router-view>
    <welcome></welcome>
        
    </div>
</template>
<script>
import UserComponent from './components/user'
import WelcomeComponent from './components/welcome'
export default {
    components:{
         'user':UserComponent,

         'welcome':WelcomeComponent
    }
}
</script>
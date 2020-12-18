<template>
    <div>
        
    <!-- <router-link to="/users">Users</router-link> - -->
  
    <router-view></router-view>
    
   
  
        
    </div>
</template>
<script>
import UserComponent from './components/user'
import WelcomeComponent from './components/welcome'
import ProductComponent from './components/product'
import Orderh from './components/ordersH'

export default {
    components:{
         'user':UserComponent,
         'product':ProductComponent,
         'welcome':WelcomeComponent,
         
    

    }
}
</script>
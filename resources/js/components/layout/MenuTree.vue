<template>
    <div>
        <template v-for="(item, index) in menus">
            <!--无子菜单-->
            <el-menu-item :index="item.route_path" v-if="! item.children">
                <font-awesome-icon fixed-width :icon="item.icon" v-if="item.icon"></font-awesome-icon>
                <span slot="title" class="ml-2">{{ $te(`menu.${item.route_name}`) ? $t(`menu.${item.route_name}`) : item.name }}</span>
            </el-menu-item>
            <!--有子菜单-->
            <el-submenu :index="item.route_path" v-else>
                <template slot="title">
                    <font-awesome-icon fixed-width :icon="item.icon" v-if="item.icon"></font-awesome-icon>
                    <span class="ml-2">{{ $te(`menu.${item.route_name}`) ? $t(`menu.${item.route_name}`) : item.name }}</span>
                </template>
                <!--递归调用-->
                <menu-tree :menus="item.children"></menu-tree>
            </el-submenu>
        </template>
    </div>
</template>

<script>
    export default {
        name: "MenuTree",
        props: {
            menus: Array
        },
    }
</script>

<style>

</style>

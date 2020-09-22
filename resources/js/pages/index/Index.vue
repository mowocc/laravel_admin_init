<template>
    <vue-simplebar>
        <div class="page-container">
            <div class="page-container-inner flex-auto">
                <div class="p-5" style="max-width: 600px">
                    <el-alert class="mb-4" type="warning" title="中控子节点后台，通过【代理管理】添加平台，【代理成员】添加管理员"></el-alert>
                    <!--<h6 class="mb-5"></h6>-->
                    <el-form label-width="60px" class="el-form-detail">
                        <el-form-item label="地址：">
                            <el-input v-model="form.address"></el-input>
                        </el-form-item>
                        <el-form-item label="账号：">
                            <el-input v-model="form.email"></el-input>
                        </el-form-item>
                        <el-form-item label="密码：">
                            <el-input v-model="form.password"></el-input>
                        </el-form-item>
                    </el-form>
                </div>
            </div>
        </div>
    </vue-simplebar>
</template>

<script>
    export default {
        name: "Index",
        data() {
            return {
                form: {
                    address: '49.234.18.219:9700/admin',
                    email: 'admin@admin.com',
                    password: 'aaaaaaaa',
                }
            }
        }
    }
</script>

<style scoped>

</style>

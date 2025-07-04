import router from './index'

router.afterEach((to, from) => {
    // console.table(to, from)
})

router.beforeEach((to, from, next) => {
    // console.log('to',to, 'from',from, 'next',next)
    next()
    // if (to.name !== 'Login' && !isAuthenticated) next({ name: 'Login' })
    // else next()
})
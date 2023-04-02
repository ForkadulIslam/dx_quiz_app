export default function log({  next }) {
    //console.log(localStorage.getItem('token'))
    if (!localStorage.getItem('token')) {
        next({ path: '/' });
    }else{
        return next();
    }
}


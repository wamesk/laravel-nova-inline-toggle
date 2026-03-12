import IndexField from './components/IndexField'
import DetailField from './components/DetailField'
import FormField from './components/FormField'

Nova.booting((app, store) => {
  app.component('index-inline-toggle', IndexField)
  app.component('detail-inline-toggle', DetailField)
  app.component('form-inline-toggle', FormField)
})

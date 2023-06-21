export type Directive = {
  group_id: number
  key: string
  value: [string] | null
  description: string | null
  active: boolean
  // eslint-disable-next-line no-use-before-define
  group: Group | null
}

export type Group = {
  id: number
  key: string
  title: string
  description: string | null
  active: boolean
  directives: [Directive] | null
}

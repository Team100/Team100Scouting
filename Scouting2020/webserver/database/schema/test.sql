
with recursive node(node, parent, position, topic, level) as (
select d1.docnode, d1.parent, d1.position, d1.topic, 1 level from docnode d1 
  left join docnode d2 on d2.docnode = d1.parent
  where d1.parent = 'TestNode'
union all
select d1.docnode, d1.parent, d1.position, d1.topic, level+1 level from docnode d1 
  join node d2 on d2.node = d1.parent
  ) 
select node, parent, position, topic, level from node order by level, position
;
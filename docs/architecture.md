we believe in layered architecture.
we have following layers:
- core
- use case
- ui
- infrastructure

dependency rule is following:
core could use only core and language core things + psr's
use case could use self, core and all it can
ui could use self, use case and all it can
infrastructure could use everything


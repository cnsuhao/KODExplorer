# String Literals
'For''
"God""
"""so loved
the world"""
'''that he gave
his only begotten' '''
'that whosoever believeth 
in him'
''

# Identifiers
__a__
a.b
a.b.c

# Operators
+ - * / % & | ^ ~ < >
== != <= >= <> << >> // **
and or not in is

# Delimiters
() [] {} , : ` = ; @ .  # Note that @ and . require the proper context.
+= -= *= /= %= &= |= ^=
//= >>= <<= **=

# Keywords
as assert break class continue def del elif else except
finally for from global if import lambda pass raise
return try while with yield

# Python 2 Keywords (otherwise Identifiers)
exec print

# Python 3 Keywords (otherwise Identifiers)
nonlocal

# Types
bool classmethod complex dict enumerate float frozenset int list object
property reversed set slice staticmethod str super tuple type

# Python 2 Types (otherwise Identifiers)
basestring buffer file long unicode xrange

# Python 3 Types (otherwise Identifiers)
bytearray bytes filter map memoryview open range zip

# Some Example code
import os
from package import ParentClass

@nonsenseDecorator
def doesNothing():
    pass

class ExampleClass(ParentClass):
    @staticmethod
    def example(inputStr):
        a = list(inputStr)
        a.reverse()
        return ''.join(a)

    def __init__(self, mixin = 'Hello'):
        self.mixin = mixin


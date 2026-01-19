git copy of one file into two files without loosing history

```
git mv foo bar
git commit

SAVED=`git rev-parse HEAD`
git reset --hard HEAD^
git mv foo copy
git commit

git merge $SAVED     # This will generate conflicts
git commit -a        # Trivially resolved like this

git mv copy foo
git commit
```

DO NOT SQUASH the branch NEVER !
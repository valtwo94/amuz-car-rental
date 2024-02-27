# 🚗 렌트카 예약 서비스 구현

## 프로젝트 버전

MY SQL => 8.0.22
PHP => 8.3.3
Node.js => 20.9.0
Laravel => 9.52.16

## 프로젝트 설명

로그인 인증은 기본 Breeze로 Generate한 틀에서 약간 수정하여 만들었습니다.

예약은 일단위 예약으로 구현을 했고, 날짜 선택시 10분 유효기간의 임시 예약으로 만들고 추가 정보 입력을 하면은 임시예약의 상태가 확정예약으로 바뀝니다.

추가 정보 입력을 안하고 앱에서 이탈한 경우 임시 예약이 남아있는 부분은 스케쥴러를 이용하여 유효기간이 지나고 임시상태인 칼럼들을 정리합니다.


## 프로젝트 실행

docker mysql image로 컨테이너를 띄우고 마이그레이션 커맨드

`php artisan migrate`

프로젝트 개발모드 실행 커맨드

`php artisan serve && npm run dev`

스케쥴러 테스트 커맨드

`php artisan schedule:run`



### Todo

- [x] **라라벨 프레임워크 설치 및 로컬 개발환경 구축**
    - [Laravel Installation Guide](https://laravel.kr/docs/9.x/installation) 참고

- [x] **Route 정의**
    - [x] `/` -> name : list -> 등록된 차량 리스트 페이지
    - [x] `/create` -> name : create -> 차량 등록 페이지
    - [x] `/show/{id}` -> name : show -> 차량별 예약 정보 확인 페이지
    - [x] `/reservation` -> name : reservation -> 차량 예약 페이지

- [x] **Migration을 사용해 table 생성**
    - [x] 테이블은 임의로 생성

- [x] **Eloquent ORM에 postModel 만들기**
    - [x] 생성된 테이블을 기반으로 Eloquent 모델 생성

- [x] **Create Route에서 차량 등록 처리**
    - [x] `/create` 라우트에서 차량 등록 기능 구현

- [x] **List Route에서 Create된 차량 List 및 예약 가능 여부 확인 처리**
    - [x] `/` 라우트에서 등록된 차량 목록과 예약 가능 여부 표시

- [x] **List Route에서 차량 클릭 시 Show Route로 이동**
    - [x] 클릭한 차량에 대한 예약 정보 확인을 위해 `/show/{id}` 라우트로 이동

- [x] **Show Route에서 "예약" 버튼 클릭 시 Reservation Route로 이동**
    - [x] 예약 가능 여부 확인 후, 예약 정보 입력을 위해 `/reservation` 라우트로 이동

- [x] **Reservation Route에서 예약 시작일/시간, 종료일/시간 설정 및 중복 예약 처리**
    - [x] 날짜 선택 시 중복 예약이 안되도록 처리

- [x] **Frontend View 구현시 Tailwind & Inertia Vue 사용**
    - [x] [Tailwind CSS](https://tailwindcss.com/) 및 [Inertia.js](https://inertiajs.com/)를 사용하여 프론트엔드 뷰 구현

- [x] **추가 기능 (선택 사항)**
    - [x] 회원가입, 로그인, 게시물 비밀번호 등의 기능 추가 가능



    
## 프로젝트 회고

정통 MVC 프레임워크를 많이 만져본적은 없는데 Django하고 비슷한 느낌이었습니다. 
구축되어있는 클래스에 대한 이해도를 높여나가면 활용도 있게 쓸 수 있을 것 같습니다.
독특한 문법을 써서 코드 이해하는데는 애를 먹었지만 익숙해지다보니 괜찮게 느껴졌습니다.

예약 시스템을 만들다보니 자연스럽게 이런 저런 라라벨 기능들을 많이 찾아서 써봤던 것 같습니다.
아쉬운 점이 있다면 시간 예약까지 해보고 싶었는데 못했다는점

